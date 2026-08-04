@extends('layouts.app')
@section('title', 'Course Content')

@section('content')

<div class="pagetitle">
    <h1>{{ $course->title }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Home</a></li>
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

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Course Curriculum</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                            <i class="bi bi-plus-circle me-1"></i> Add Section
                        </button>
                    </div>

                    {{-- Sections Accordion Container --}}
                    <div class="accordion accordion-flush" id="sectionsAccordion">
                        @forelse($sections as $section)
                            <div class="accordion-item border mb-3 rounded shadow-sm">

                                {{-- Section Header --}}
                                <div class="accordion-header bg-light p-3 d-flex justify-content-between align-items-center" id="heading{{ $section->id }}">
                                    <div class="d-flex align-items-center flex-grow-1" data-bs-toggle="collapse" data-bs-target="#collapse{{ $section->id }}" style="cursor: pointer;">
                                        <i class="bi bi-chevron-down me-2 text-secondary"></i>
                                        <div>
                                            <span class="fw-bold text-dark">{{ $section->title }}</span>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-2 small">
                                                {{ $section->lessons->count() }} Lessons
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Section Actions --}}
                                    <div class="ms-3">
                                        <button class="btn btn-sm btn-link text-primary p-1 me-2" data-bs-toggle="modal" data-bs-target="#addLessonModal{{ $section->id }}" title="Add Lesson to this section">
                                            <i class="bi bi-plus-square fs-5"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning p-1 px-2 me-1" data-bs-toggle="modal" data-bs-target="#editSectionModal{{ $section->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('sections.destroy', $section) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete whole section {{ $section->title }} and its lessons?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Section Content (Lessons List) --}}
                                <div id="collapse{{ $section->id }}" class="accordion-collapse collapse show" data-bs-parent="#sectionsAccordion">
                                    <div class="accordion-body p-0 border-top">

                                        @if($section->description)
                                            <div class="p-3 bg-light-subtle text-muted small border-b">
                                                <strong>Description:</strong> {{ $section->description }}
                                            </div>
                                        @endif

                                        <ul class="list-group list-group-flush mb-0">
                                            @forelse($section->lessons as $lesson)
                                                <li class="list-group-item d-flex justify-content-between align-items-center py-3 ps-4">
                                                    <div class="d-flex align-items-center">
                                                        {{-- Icon dynamically based on lesson type/source --}}
                                                        @if($lesson->video_source === 'youtube' || $lesson->video_source === 'vimeo')
                                                            <i class="bi bi-play-btn text-danger fs-5 me-3"></i>
                                                        @else
                                                            <i class="bi bi-file-earmark-play text-primary fs-5 me-3"></i>
                                                        @endif

                                                        <div>
                                                            <h6 class="mb-0 fw-semibold text-secondary">{{ $lesson->title }}</h6>
                                                            @if($lesson->duration)
                                                                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $lesson->duration }} mins</small>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Lesson Actions --}}
                                                    <div>
                                                        <button class="btn btn-sm btn-light border p-1 px-2 me-1" data-bs-toggle="modal" data-bs-target="#editLessonModal{{ $lesson->id }}">
                                                            <i class="bi bi-pencil small text-dark"></i>
                                                        </button>
                                                        <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this lesson?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-light border p-1 px-2 text-danger">
                                                                <i class="bi bi-trash small"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="list-group-item text-center text-muted py-3 small bg-light-subtle">
                                                    <i class="bi bi-info-circle me-1"></i> No lessons in this section yet. Click the <i class="bi bi-plus-square"></i> icon above to add one.
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
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
                                                    <input type="text" name="title" class="form-control" value="{{ $section->title }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea name="description" class="form-control" rows="3">{{ $section->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i> Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- 📥 Add Lesson Modal For Each Section --}}
                            <div class="modal fade" id="addLessonModal{{ $section->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add New Lesson to: <strong>{{ $section->title }}</strong></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
<form action="{{ route('lessons.store', [$course->id, $section->id]) }}" method="POST" enctype="multipart/form-data">                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-8 mb-3">
                                                        <label class="form-label">Lesson Title <span class="text-danger">*</span></label>
                                                        <input type="text" name="title" class="form-control" placeholder="e.g. Setting Up Environment" required>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">Duration (Minutes)</label>
                                                        <input type="number" name="duration" class="form-control" placeholder="e.g. 15">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Video Source</label>
                                                    <select name="video_source" class="form-select">
                                                        <option value="upload">Direct Upload (Video File)</option>
                                                        <option value="youtube">YouTube Link</option>
                                                        <option value="vimeo">Vimeo Link</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Video URL / File</label>
                                                    <input type="text" name="video_url" class="form-control mb-2" placeholder="Paste YouTube/Vimeo URL if selected">
                                                    <input type="file" name="video_file" class="form-control" accept="video/*">
                                                    <small class="text-muted">Fill the text field for streaming links, OR upload a file directly.</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Attachments / PDF Notes</label>
                                                    <input type="file" name="pdf_file" class="form-control" accept=".pdf,.zip">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> Add Lesson</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div class="text-center text-muted py-5 border rounded bg-light">
                                <i class="bi bi-collection fs-1 d-block mb-2 text-secondary"></i>
                                No sections yet. Add your first section to start building the curriculum!
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>

        {{-- Side Info Card --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center pt-4">
                    <span class="badge {{ $course->is_published ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} border mb-3 px-3 py-2 fs-6 rounded-pill">
                        <i class="bi bi-circle-fill me-1 small"></i>{{ $course->is_published ? 'Published' : 'Draft Mode' }}
                    </span>
                    <h5 class="fw-bold text-dark mb-4">{{ $course->title }}</h5>

                    <div class="d-flex justify-content-between text-muted border-bottom pb-2 mb-2 small">
                        <span>Total Sections:</span>
                        <strong class="text-dark">{{ $sections->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between text-muted border-bottom pb-2 mb-2 small">
                        <span>Total Lessons:</span>
                        <strong class="text-dark">{{ $sections->sum('lessons_count') }}</strong>
                    </div>

                    <div class="mt-4 pt-2 gap-2 d-grid">
                        <form action="{{ route('courses.togglePublish', $course) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn w-100 {{ $course->is_published ? 'btn-outline-secondary' : 'btn-success' }}">
                                <i class="bi {{ $course->is_published ? 'bi-eye-slash' : 'bi-cloud-arrow-up' }} me-1"></i>
                                {{ $course->is_published ? 'Unpublish Course' : 'Publish Course' }}
                            </button>
                        </form>
                        <a href="{{ route('instructor.dashboard') }}" class="btn btn-light border text-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                        </a>
                    </div>
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
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Introduction" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief outline of this section..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Add Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
