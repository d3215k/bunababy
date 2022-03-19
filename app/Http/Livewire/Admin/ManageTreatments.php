<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Treatment;
use Livewire\Component;
use Livewire\WithPagination;

class ManageTreatments extends Component
{
    use WithPagination;

    public $perPage = 3;

    public $showDialog = false;
    public $successMessage = false;

    public $filterSearch;
    public $filterCategory;
    public $filterStatus;

    public $state = [];

    protected $queryString = [
        'filterSearch' => ['except' => ''],
        'page' => ['except' => 1],
        'perPage' => ['except' => 3],
        'filterCategory' => ['except' => ''],
        'filterStatus' => ['except' => ''],
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

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingFilterSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedStateCategoryId()
    {
        $this->state['order'] = Treatment::where('category_id', $this->state['category_id'])->max('order') + 1;
    }

    public function showAddNewTreatmentDialog()
    {
        $this->showDialog = true;
        $this->state = [];
        $this->state['active'] = true;
    }

    public function ShowEditTreatmentDialog( Treatment $treatment)
    {
        $this->state = $treatment->toArray();
        $this->showDialog = true;
    }

    public function save()
    {
        $this->validate();

        Treatment::updateOrCreate(
            [
                'id' => $this->state['id'] ?? Treatment::max('id') + 1,
            ],
            [
                'category_id' => $this->state['category_id'],
                'name' => $this->state['name'],
                'price' => $this->state['price'],
                'duration' => $this->state['duration'],
                'desc' => $this->state['desc'],
                'order' => $this->state['order'],
                'active' => $this->state['active'],
            ]
        );

        $this->showDialog = false;
        $this->successMessage = true;
    }

    public function render()
    {
        $treatments = Treatment::query()
            ->where(function($query){
                $query
                ->where('name', 'LIKE', '%' . $this->filterSearch . '%')
                ->orWhere('desc', 'LIKE', '%' . $this->filterSearch . '%')
                ->orWhere('price', 'LIKE', '%' . $this->filterSearch . '%')
                ->orWhere('duration', 'LIKE', '%' . $this->filterSearch . '%');
            })
            ->Where('category_id', 'LIKE', '%' . $this->filterCategory . '%')
            ->Where('active', 'LIKE', '%' . $this->filterStatus . '%')
            ->with('category')
            ->orderBy('category_id')->orderBy('order')
            ->paginate($this->perPage);

        return view('admin.treatments', [
            'treatments' => $treatments,
        ])->layout('layouts.app');
    }
}