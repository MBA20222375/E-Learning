@extends('layouts.app')
@section('title', 'Course Content')

@section('content')

<div class="pagetitle">
    <h1>{{ $course->title }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('instructor.courses.index') }}">My Courses</a></li>
            <li class="breadcrumb-item active">Content</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
                        <h5 class="card-title p-0 m-0">Course Curriculum</h5>
                        <button class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#addSectionModal">
                            <i class="bi bi-plus-circle me-1"></i> Add Section
                        </button>
                    </div>

                    {{-- Sections List --}}
                    @forelse($sections as $section)
                        <div class="card mb-4 border shadow-none">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                <div>
                                    <i class="bi bi-collection me-2 text-primary"></i>
                                    <strong>{{ $section->title }}</strong>
                                    <span class="badge bg-primary ms-2">
                                        {{ $section->lessons->count() }} lessons
                                    </span>
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('lessons.create', [$course, $section]) }}" class="btn btn-sm btn-success" title="Add Lesson to this section">
                                        <i class="bi bi-plus-lg"></i> Add Lesson
                                    </a>
                                    <button class="btn btn-sm btn-outline-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editSectionModal{{ $section->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('sections.destroy', $section) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete \'{{ $section->title }}\' and all its lessons?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if($section->description)
                                <div class="card-body py-2 bg-white border-bottom">
                                    <p class="text-muted small mb-0">{{ $section->description }}</p>
                                </div>
                            @endif

                            {{-- لستة الدروس التابعة للسيكشن (Pure PHP) --}}
                            <div class="card-body p-0 bg-white">
                                <ul class="list-group list-group-flush">
                                    @forelse($section->lessons as $lesson)
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                            <div class="text-truncate" style="max-width: 75%;">
                                                <span class="text-secondary me-2 fw-bold">#{{ $lesson->order }}</span>
                                                <span class="me-1">{{ $lesson->type === 'video' ? '🎥' : '📄' }}</span>
                                                <span class="text-dark">{{ $lesson->title }}</span>
                                                @if($lesson->is_free_preview)
                                                    <span class="badge bg-warning text-dark ms-2 small" style="font-size: 0.75rem;">🔓 Free Preview</span>
                                                @endif
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-light text-secondary border">{{ $lesson->duration }} mins</span>
                                                <form action="{{ route('lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('Delete lesson: {{ $lesson->title }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0 m-0" title="Delete Lesson">
                                                        <i class="bi bi-trash fs-6"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted text-center py-3 bg-light bg-opacity-25 small">
                                            No lessons added to this section yet. Click "Add Lesson" to start.
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        {{-- Edit Section Modal --}}
                        <div class="modal fade" id="editSectionModal{{ $section->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Section</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('sections.update', $section) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                                <input type="text" name="title"
                                                       class="form-control"
                                                       value="{{ $section->title }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description"
                                                          class="form-control"
                                                          rows="3">{{ $section->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bi bi-save me-1"></i> Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="text-center text-muted py-5 border rounded bg-light bg-opacity-25">
                            <i class="bi bi-collection fs-1 d-block mb-2 text-secondary"></i>
                            No sections yet. Add your first section using the button above!
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

        {{-- Info Card --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title border-bottom pb-2 mb-3">Course Actions</h5>

                    <a href="{{ route('courses.preview', $course) }}" class="btn btn-outline-info btn-sm w-100 mb-3 py-2 fw-semibold">
                        <i class="bi bi-eye me-1"></i> 👁️ Live Preview Mode
                    </a>

                    <div class="row mb-2 small">
                        <div class="col-5 text-muted">Status</div>
                        <div class="col-7">
                            <span class="badge {{ $course->is_published ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $course->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-2 small">
                        <div class="col-5 text-muted">Total Sections</div>
                        <div class="col-7 fw-bold">{{ $sections->count() }}</div>
                    </div>
                    <div class="row mb-3 small">
                        <div class="col-5 text-muted">Students</div>
                        <div class="col-7 fw-bold">{{ $course->enrollments()->count() }}</div>
                    </div>

                    <hr class="my-3">

                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning btn-sm w-100 mb-2">
                        <i class="bi bi-pencil me-1"></i> Edit Course Info
                    </a>

                    <form action="{{ route('courses.togglePublish', $course) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="btn btn-sm w-100 {{ $course->is_published ? 'btn-secondary' : 'btn-success' }}">
                            {{ $course->is_published ? '📦 Unpublish Course' : '🚀 Publish Course' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- Add Section Modal --}}
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sections.store', $course) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="e.g. Chapter 1: Introduction" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description"
                                  class="form-control"
                                  placeholder="Briefly describe what this section covers..."
                                  rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Add Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
