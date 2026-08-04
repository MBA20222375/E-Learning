<div>

    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                <h5 class="card-title mb-0">All Categories</h5>
                <button wire:click="$set('showCreateModal', true)" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add New Category
                </button>
            </div>

            <div class="mb-3">
                <input wire:model.live="search"
                       type="text"
                       class="form-control"
                       placeholder="Search categories...">
            </div>

            <table class="table table-hover table-borderless">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Courses</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr wire:key="category-{{ $category->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $category->name }}</td>
                            <td class="text-muted">{{ $category->description ?? '—' }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $category->courses_count }} courses
                                </span>
                            </td>
                            <td>
                                <button wire:click="startEdit({{ $category->id }})"
                                        class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button wire:click="delete({{ $category->id }})"
                                        wire:confirm="Are you sure you want to delete {{ $category->name }}?"
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-tags fs-1 d-block mb-2"></i>
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $categories->links() }}
            </div>

        </div>
    </div>

    {{-- Create Modal --}}
    @if($showCreateModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Category</h5>
                        <button wire:click="$set('showCreateModal', false)" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input wire:model="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="e.g. Web Development">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea wire:model="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Short description..."></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="$set('showCreateModal', false)" class="btn btn-secondary">Cancel</button>
                        <button wire:click="create" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Modal --}}
    @if($showEditModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button wire:click="$set('showEditModal', false)" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input wire:model="editName" type="text"
                                   class="form-control @error('editName') is-invalid @enderror">
                            @error('editName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea wire:model="editDescription"
                                      class="form-control @error('editDescription') is-invalid @enderror"
                                      rows="3"></textarea>
                            @error('editDescription')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="$set('showEditModal', false)" class="btn btn-secondary">Cancel</button>
                        <button wire:click="update" class="btn btn-warning">
                            <i class="bi bi-save me-1"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
