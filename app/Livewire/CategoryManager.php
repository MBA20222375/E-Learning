<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showCreateModal = false;
    public bool $showEditModal   = false;

    public string $name        = '';
    public string $description = '';

    public ?int $editId          = null;
    public string $editName        = '';
    public string $editDescription = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $validated = $this->validate([
            'name'        => 'required|string|min:2|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        Category::create($validated);
        $this->reset(['name', 'description']);
        $this->showCreateModal = false;
    }

    public function startEdit(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->editId          = $id;
        $this->editName        = $category->name;
        $this->editDescription = $category->description ?? '';
        $this->showEditModal   = true;
    }

    public function update(): void
    {
        $validated = $this->validate([
            'editName'        => 'required|string|min:2|max:255|unique:categories,name,' . $this->editId,
            'editDescription' => 'nullable|string|max:500',
        ]);

        Category::findOrFail($this->editId)->update([
            'name'        => $this->editName,
            'description' => $this->editDescription,
        ]);

        $this->reset(['editId', 'editName', 'editDescription']);
        $this->showEditModal = false;
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();
    }

    public function render()
    {
        $categories = Category::withCount('courses')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.category-manager', [
            'categories' => $categories,
        ]);
    }
}
