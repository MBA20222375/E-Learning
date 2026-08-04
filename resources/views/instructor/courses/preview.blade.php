@extends('layouts.app')
@section('title', 'Course Preview - ' . $course->title)

@section('content')
    

<div class="pagetitle mb-4">
    <h1>{{ $course->title }}</h1>
    <p class="text-muted mb-0">Level: <span class="badge bg-primary">{{ ucfirst($course->level) }}</span> | Language: <strong>{{ $course->language ?? 'English' }}</strong></p>
</div>

<section class="section">
    <div class="row">

        <div class="col-lg-8">

            <div class="card shadow-sm mb-4">
                <div class="card-body p-0 text-center bg-black rounded-top d-flex align-items-center justify-content-center" style="min-height: 400px;">
                    <div class="text-white p-4">
                        <i class="bi bi-play-circle display-1 text-warning mb-3 d-block"></i>
                        <h4 class="fw-bold">Student Media Player Window</h4>
                        <p class="text-muted small px-md-5">
                            When a student clicks on any lesson from the curriculum sidebar on the right, the video file (`video_path`) or text content (`content`) will stream and load dynamically right here.
                        </p>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <h5 class="fw-bold text-dark">Course Short Description</h5>
                    <p class="text-muted">{{ $course->short_description ?? 'No short description provided yet.' }}</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title text-primary"> What Students Will Learn</h5>
                    <p class="text-dark" style="white-space: pre-line;">{{ $course->what_you_learn ?? 'Not specified.' }}</p>

                    <hr class="my-4 text-muted">

                    <h5 class="card-title text-danger"> Requirements</h5>
                    <p class="text-dark" style="white-space: pre-line;">{{ $course->requirements ?? 'No specific prerequisites.' }}</p>
                </div>
            </div>

        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white pt-3 border-0">
                    <h5 class="fw-bold mb-0 text-dark">Course Curriculum</h5>
                    <span class="text-muted small">Total Sections: {{ $course->sections->count() }}</span>
                </div>
                <div class="card-body px-2 pb-3">

                    <div class="accordion accordion-flush" id="courseCurriculumAccordion">

                        @forelse($course->sections as $index => $section)
                            <div class="accordion-item border mb-2 rounded">
                                <h2 class="accordion-header" id="heading{{ $section->id }}">
                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }} bg-light fw-semibold text-dark"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $section->id }}">
                                        📁 {{ $section->title }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $section->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                     data-bs-parent="#courseCurriculumAccordion">
                                    <div class="accordion-body p-0">

                                        <div class="list-group list-group-flush">
                                            @forelse($section->lessons as $lesson)
                                                <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                                    <div class="text-truncate" style="max-width: 70%;">
                                                        <span class="me-1">
                                                            {{ $lesson->type === 'video' ? '🎥' : '📄' }}
                                                        </span>
                                                        <span class="small text-dark fw-medium" title="{{ $lesson->title }}">
                                                            {{ $lesson->title }}
                                                        </span>
                                                    </div>

                                                    <div class="d-flex align-items-center gap-1">
                                                        @if($lesson->is_free_preview)
                                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2 small">
                                                                Free
                                                            </span>
                                                        @endif
                                                        <span class="badge bg-light text-secondary border small">
                                                            {{ $lesson->duration }}m
                                                        </span>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="list-group-item text-muted small text-center py-2 bg-white">
                                                    No lessons added yet.
                                                </div>
                                            @endforelse
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center p-4 text-muted">
                                <i class="bi bi-folder-x display-6"></i>
                                <p class="mt-2 mb-0 small">No curriculum created for this course yet.</p>
                            </div>
                        @endforelse

                    </div></div>
            </div>
        </div>

    </div>
</section>
@endsection
