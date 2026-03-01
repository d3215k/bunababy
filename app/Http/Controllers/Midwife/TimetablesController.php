<?php

namespace App\Http\Controllers\Midwife;

use App\Enums\TimetableType;
use App\Http\Controllers\Controller;
use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetablesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $timetables = Timetable::query()
            ->where('midwife_id', auth()->user()->midwife_id)
            ->orderBy('date', 'ASC')
            ->get();

        return view('midwife.timetables', [
            'timetables' => $timetables,
            'count' => $timetables->count(),
            'overtime' => $timetables->where('type', TimetableType::OVERTIME)->count(),
        ]);
    }
}
