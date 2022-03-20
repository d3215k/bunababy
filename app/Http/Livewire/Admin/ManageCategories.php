<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Treatment;
use Livewire\Component;

class ManageCategories extends Component
{
    public $showDialog = false;
    public $successMessage = false;

    public $filterStatus;

    public $state = [];

    protected $queryString = [];

    protected $rules = [
        'state.name' => 'required',
        'state.desc' => 'nullable',
        'state.order' => 'required',
        'state.active' => 'required',
    ];

    protected $messages = [];

    protected $validationAttributes = [];

    public function showAddNewCategoryDialog()
    {
        $this->showDialog = true;
        $this->state = [];
        $this->state['order'] = Category::max('id') + 1;
        $this->state['active'] = true;
    }

    public function ShowEditCategoryDialog( Category $category)
    {
        $this->state = $category->toArray();
        $this->showDialog = true;
    }

    public function save()
    {
        $this->validate();

        Category::updateOrCreate(
            [
                'id' => $this->state['id'] ?? Category::max('id') + 1,
            ],
            [
                'name' => $this->state['name'],
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
        $categories = Category::query()
            ->get();

        return view('admin.categories', [
            'categories' => $categories
        ])->layout('layouts.app');
    }
}