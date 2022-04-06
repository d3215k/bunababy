<?php

namespace App\Http\Livewire\Notifications;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class ManageNotifications extends Component
{
    use WithPagination;

    public $perPage = 8;

    public $showDialog = false;
    public $successMessage = false;

    public $filterStatus;
    public $filterSearch;
    public $filterType;

    public $selectedNotifications = [];

    public $state = [];

    protected $queryString = [
        'page' => ['except' => 1],
        'perPage' => ['except' => 3],
    ];

    protected $rules = [
        'state.name' => 'required',
        'state.desc' => 'required',
        'state.price' => 'required',
        'state.duration' => 'required',
        'state.order' => 'required',
        'state.category_id' => 'required',
        'state.active' => 'required',
    ];

    protected $messages = [
        //
    ];

    protected $validationAttributes = [
        'state.name' => 'nama',
        'state.desc' => 'deskripsi',
        'state.price' => 'harga',
        'state.duration' => 'durasi',
        'state.order' => 'urutan',
        'state.category_id' => 'kategori',
        'state.active' => 'status aktif',
    ];

    public function markAsRead($notificationId)
    {
        $notification = DatabaseNotification::findOrFail($notificationId);
        $notification->markAsRead();
        $this->emit('refreshSidebar');
    }

    public function markAsUnRead($notificationId)
    {
        $notification = DatabaseNotification::findOrFail($notificationId);
        $notification->update(['read_at' => NULL ]);
        $this->emit('refreshSidebar');
    }

    public function delete($notificationId)
    {
        $notification = DatabaseNotification::findOrFail($notificationId);
        $notification->delete();
        $this->emit('refreshSidebar');
    }

    public function deleteSelectedNotificatons()
    {
        foreach($this->selectedNotifications as $notification => $boolean){
            if($boolean) {
                $notification = DatabaseNotification::find($notification);
                $notification->delete();
            }
        }

        $this->selectedNotifications = '';
        $this->emit('refreshSidebar');
    }

    public function save()
    {
        $this->validate();

        $this->showDialog = false;
        $this->successMessage = true;
    }

    public function render()
    {
        if($this->filterStatus == 'unread') {
            $query = auth()->user()->unreadNotifications();
        } else if ($this->filterStatus == 'read') {
            $query = auth()->user()->readNotifications();
        } else {
            $query = auth()->user()->notifications();
        }

        $notifications = $query
        ->where('data', 'LIKE', '%' . $this->filterSearch . '%')
        ->where('data->type', 'LIKE', '%' . $this->filterType . '%')
        ->paginate($this->perPage);

        return view('notifications.manage-notifications', [
            'notifications' => $notifications,
        ]);
    }
}
