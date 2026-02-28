<?php

namespace App\Services;

use App\Enums\PlaceType;
use App\Enums\TimetableType;
use App\Exceptions\NoSlotException;
use App\Models\Address;
use App\Models\Order;
use App\Models\Place;
use App\Models\Timetable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderService
{
    /**
     * Prepare order data with calculations.
     *
     * @param  array  $data  Order data
     * @param  Order|null  $currentOrder  Current order for update context
     * @return array Prepared data with calculated fields
     */
    private function prepareOrderData(array $data, ?Order $currentOrder = null): array
    {
        $address = Address::find($data['address_id']);
        $place = Place::find($data['place_id']);

        // Handle homecare place type
        if ($place->type === PlaceType::HOMECARE) {
            // Recalculate transport only if place changed or new order
            if (! $currentOrder || $currentOrder->place_id !== $data['place_id']) {
                $data['transport'] = Order::getCalculatedTransport($address->kecamatan->distance);
            }
            // Clear room_id for homecare
            $data['room_id'] = null;
        }

        // Calculate total price from all treatments
        $data['price'] = collect($data['treatments'])->sum('treatment_price');

        // Calculate end time based on treatment duration and transport
        $data['end_time'] = Order::getCalculatedEndTime(
            $data['date'],
            $data['start_time'],
            $data['treatments'],
            $place->transport_duration
        );

        return $data;
    }

    /**
     * Validate slot availability for midwife.
     *
     * @param  array  $data  Order data
     * @param  int|null  $excludeOrderId  Order ID to exclude from check (for updates)
     * @return bool Whether slot is available
     */
    private function validateSlotAvailability(array $data, ?int $excludeOrderId = null): bool
    {
        // Check if midwife is on leave
        $isMidwifeOnLeave = Timetable::query()
            ->where('midwife_id', $data['midwife_id'])
            ->where('date', $data['date'])
            ->whereIn('type', [TimetableType::LEAVE])
            ->exists();

        if ($isMidwifeOnLeave) {
            return false;
        }

        // Check if midwife has clinic-only timetable (can only work at specific clinic)
        $clinicTimetable = Timetable::query()
            ->where('midwife_id', $data['midwife_id'])
            ->where('date', $data['date'])
            ->where('type', TimetableType::CLINIC)
            ->first();

        if ($clinicTimetable) {
            // Midwife can only work at the clinic specified in timetable
            $place = Place::find($data['place_id']);

            // If order is homecare, reject
            if ($place->type === PlaceType::HOMECARE) {
                return false;
            }

            // If order is clinic but different clinic, reject
            if ($clinicTimetable->place_id && $clinicTimetable->place_id !== $data['place_id']) {
                return false;
            }
        }

        // Check for time slot conflicts
        $startDateTime = Carbon::parse($data['date'].' '.$data['start_time']);
        $endDateTime = Carbon::parse($data['date'].' '.$data['end_time']);

        // 1. Check midwife availability across all places
        // A midwife can't be in multiple places at once
        $midwifeConflicts = Order::where('date', $data['date'])
            ->where('midwife_id', $data['midwife_id'])
            ->when($excludeOrderId,
                fn ($query) => $query->where('id', '!=', $excludeOrderId),
            )
            ->activeBetween($startDateTime->toTimeString(), $endDateTime->toTimeString())
            ->count();

        if ($midwifeConflicts > 0) {
            return false;
        }

        // 2. Check room availability for clinic orders
        // A room can only be used by one order at a time (regardless of which midwife)
        if (isset($data['room_id']) && $data['room_id']) {
            $roomConflicts = Order::where('date', $data['date'])
                ->where('room_id', $data['room_id'])
                ->when($excludeOrderId,
                    fn ($query) => $query->where('id', '!=', $excludeOrderId),
                )
                ->activeBetween($startDateTime->toTimeString(), $endDateTime->toTimeString())
                ->count();

            if ($roomConflicts > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Create a new order with slot availability validation.
     *
     * @param  array{
     *     customer_name: string,
     *     customer_phone: string,
     *     customer_email: string,
     *     address_id: int,
     *     place_id: int,
     *     midwife_id: int,
     *     date: string,
     *     start_time: string,
     *     place_type: string,
     *     full_address: string,
     *     midwife_name: string,
     *     place_name: string,
     *     room_id: ?int,
     *     room_name: ?string,
     *     screening: array<array{keluhan: string, penyakit_menular: bool, riwayat_imunisasi: bool}>,
     *     treatments: array<array{treatment_id: int, treatment_name: string, treatment_price: float, family_id: int, family_name: string, family_dob: ?string}>,
     * }  $data
     * @return Order The created order
     *
     * @throws NoSlotException When slot is not available
     * @throws Throwable Other errors
     */
    public function create(array $data): Order
    {
        DB::beginTransaction();

        try {
            // Prepare order data with calculations
            $data = $this->prepareOrderData($data);

            // Set the user who created the order
            $data['created_by'] = Auth::id();

            // Validate slot availability for midwife
            if (! $this->validateSlotAvailability($data)) {
                Log::info('Order creation failed: No slot available', $data);
                throw new NoSlotException('Slot reservasi tersedia tidak cukup!');
            }

            // Create order in database
            $order = Order::create($data);
            DB::commit();

            return $order;
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update an existing order with slot availability validation.
     *
     * @param  Model  $record  The order record to update
     * @param  array{
     *     customer_name: string,
     *     customer_phone: string,
     *     customer_email: string,
     *     address_id: int,
     *     place_id: int,
     *     midwife_id: int,
     *     date: string,
     *     start_time: string,
     *     place_type: string,
     *     full_address: string,
     *     midwife_name: string,
     *     place_name: string,
     *     room_id: ?int,
     *     room_name: ?string,
     *     screening: array<array{keluhan: string, penyakit_menular: bool, riwayat_imunisasi: bool}>,
     *     treatments: array<array{treatment_id: int, treatment_name: string, treatment_price: float, family_id: int, family_name: string, family_dob: ?string}>,
     * }  $data
     * @return Model The updated order
     *
     * @throws \Exception Validation or database errors
     */
    public function update(Model $record, array $data): Model
    {
        DB::beginTransaction();

        try {
            if (! array_key_exists('treatments', $data) || ! is_array($data['treatments'])) {
                $data['treatments'] = $record->treatments ?? [];
            }

            if (! array_key_exists('screening', $data) || ! is_array($data['screening'])) {
                $data['screening'] = $record->screening ?? [];
            }

            // Prepare order data with calculations
            $data = $this->prepareOrderData($data, $record);

            // Set the user who updated the order
            $data['last_updated_by'] = Auth::id();

            // Validate slot availability for midwife (excluding current record)
            if (! $this->validateSlotAvailability($data, $record->id)) {
                throw new \Exception('Jadwal tidak tersedia! Silahkan pilih jadwal lain');
            }

            // Update order in database
            $record->update($data);
            DB::commit();

            return $record;
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
