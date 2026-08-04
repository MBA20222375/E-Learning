@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">

        <div class="col-md-8">
            <h1>{{ $course->title }}</h1>

            <p class="text-muted">
                <i class="bi bi-grid"></i> {{ $course->category->name ?? '—' }} &nbsp;|&nbsp;
                <i class="bi bi-person"></i> {{ $course->instructor->name ?? '—' }} &nbsp;|&nbsp;
                <i class="bi bi-bar-chart"></i> {{ ucfirst($course->level ?? '—') }} &nbsp;|&nbsp;
                <i class="bi bi-clock"></i> {{ $course->duration_hours }}h {{ $course->duration_minutes }}m &nbsp;|&nbsp;
                <i class="bi bi-calendar-event"></i> Last updated: {{ $course->updated_at->format('M Y') }}
            </p>

            @if($course->short_description)
                <p class="lead text-secondary">{{ $course->short_description }}</p>
            @endif

            <hr>

            @if($course->what_you_learn)
                <div class="card mt-4 border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success"><i class="bi bi-check-circle-fill me-2"></i>What You'll Learn</h5>
                        <div class="row">
                            @foreach(explode("\n", $course->what_you_learn) as $item)
                                @if(trim($item))
                                    <div class="col-md-6 mb-2">
                                        <i class="bi bi-check2 text-success me-2"></i>{{ trim($item) }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Course Content (Sections & Lessons) --}}
            <h4 class="mt-4 mb-3">Course Content</h4>
            @forelse($course->sections as $section)
                <div class="mb-3">
                    <div class="bg-light px-3 py-2 fw-bold border rounded-top d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-collection me-2 text-secondary"></i>{{ $section->title }}</span>
                        <span class="badge bg-secondary text-white fw-normal">{{ $section->lessons_count }} Lessons</span>
                    </div>
                    <ul class="list-group list-group-flush border border-top-0 rounded-bottom">
                        @forelse($section->lessons as $lesson)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    @if($lesson->is_free_preview)
                                        <span class="text-success me-2" title="Free Preview">
                                            <i class="bi bi-play-btn-fill"></i>
                                        </span>
                                    @else
                                        <span class="text-muted me-2" title="Locked">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                    @endif

                                    {{ $lesson->title }}

                                    @if($lesson->is_free_preview)
                                        <span class="badge bg-light text-success border ms-2">Preview</span>
                                    @endif
                                </div>

                                @if($lesson->duration)
                                    <span class="text-muted small">
                                        <i class="bi bi-stopwatch me-1"></i>{{ $lesson->duration }} min
                                    </span>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No lessons in this section yet.</li>
                        @endforelse
                    </ul>
                </div>
            @empty
                <p class="text-muted">No content available yet.</p>
            @endforelse

            {{-- Requirements --}}
            @if($course->requirements)
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Requirements</h5>
                        <ul class="mb-0">
                            @foreach(explode("\n", $course->requirements) as $item)
                                @if(trim($item))
                                    <li>{{ trim($item) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Full Description --}}
            <h4 class="mt-4">Description</h4>
            <div class="card border-0 bg-transparent">
                <div class="card-body px-0 py-2">
                    {!! $course->description !!}
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">

                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" alt="{{ $course->title }}">
                @else
                    <div class="bg-secondary text-white text-center py-5 rounded-top">
                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                        <p class="mb-0 small">No Image Available</p>
                    </div>
                @endif

                <div class="card-body">
                    <h2 class="text-center fw-bold mb-3 text-primary">
                        {{ $course->price == 0 ? 'Free' : '$' . $course->price }}
                    </h2>

                    @auth
                        @if($isEnrolled)
                            @if($firstLesson)
                                <a href="{{ route('learn', [$course->id, $firstLesson->id]) }}"
                                   class="btn btn-success btn-lg w-100 py-2 mb-2">
                                    <i class="bi bi-play-fill me-1"></i> Go to Course
                                </a>
                            @else
                                <button class="btn btn-success btn-lg w-100 py-2 mb-2" disabled>
                                    <i class="bi bi-play-fill me-1"></i> Go to Course
                                </button>
                            @endif
                        @else
                            <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-primary btn-lg w-100 py-2 mb-2">
                                    <i class="bi bi-journal-plus me-1"></i> Enroll Now
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 py-2 mb-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login to Enroll
                        </a>
                    @endauth

                    <div class="mt-4">
                        <h6 class="fw-bold">This course includes:</h6>
                        <ul class="list-unstyled text-muted small lh-lg">
                            <li><i class="bi bi-telephone-inbound text-primary me-2"></i> Full lifetime access</li>
                            <li><i class="bi bi-laptop text-primary me-2"></i> Access on mobile and TV</li>
                            <li><i class="bi bi-award text-primary me-2"></i> Certificate of completion</li>
                        </ul>
                    </div>

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'instructor']))
                        @if($course->instructor_id === auth()->id() || auth()->user()->role === 'admin')
                            <hr>
                            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-pencil me-1"></i> Edit Course Info
                            </a>
                        @endif
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
