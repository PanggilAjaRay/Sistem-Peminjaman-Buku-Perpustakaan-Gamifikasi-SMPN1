<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class CategoryForm extends Component
{
    use WithPagination;

    public $name, $description;
    public $categoryId;
    public $isOpen = false;
    public $search = '';
    
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ];

        // If editing, exclude current category from unique validation
        if ($this->categoryId) {
            $rules['name'] = 'required|string|max:255|unique:categories,name,' . $this->categoryId;
        }

        return $rules;
    }

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.category-form', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->categoryId = null;
    }

    public function store()
    {
        $this->validate();

        Category::updateOrCreate(['id' => $this->categoryId], [
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('message', 
            $this->categoryId ? 'Kategori berhasil diperbarui.' : 'Kategori berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->description = $category->description;

        $this->openModal();
    }

    public function delete($id)
    {
        $category = Category::find($id);
        
        // Check if category has books
        if ($category->books()->count() > 0) {
            session()->flash('error', 'Kategori tidak dapat dihapus karena masih memiliki buku.');
            return;
        }
        
        $category->delete();
        session()->flash('message', 'Kategori berhasil dihapus.');
    }
}
