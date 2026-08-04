@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">

    {{-- Top Bar --}}
    <div class="bg-dark text-white px-4 py-2 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('my-courses') }}" class="text-white text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i> My Courses
            </a>
            <span class="text-secondary">|</span>
            <a href="{{ route('courses.show', $course->id) }}" class="text-white text-decoration-none">
                {{ $course->title }}
            </a>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="small text-secondary">{{ $enrollment->progress }}% complete</span>
            <div class="progress" style="width:150px; height:6px;">
                <div class="progress-bar bg-success" style="width: {{ $enrollment->progress }}%"></div>
            </div>
        </div>
    </div>

    <div class="d-flex" style="height: calc(100vh - 56px);">

        {{-- Left Sidebar --}}
        <div class="border-end overflow-auto" style="width: 320px; min-width: 320px;">
            <div class="p-3 bg-light border-bottom">
                <h6 class="mb-0 fw-bold">Course Content</h6>
            </div>
            @foreach($sections as $section)
                <div class="border-bottom">
                    <div class="px-3 py-2 bg-light fw-semibold small text-secondary">
                        <i class="bi bi-collection me-1"></i>{{ $section->title }}
                    </div>
                    @foreach($section->lessons as $lessonItem)
                        @php $done = in_array($lessonItem->id, $completedLessons); @endphp
                        <a href="{{ route('learn', [$course->id, $lessonItem->id]) }}"
                           class="d-flex align-items-center px-3 py-2 text-decoration-none border-bottom
                                  {{ $lessonItem->id === $lesson->id ? 'bg-primary text-white' : 'text-dark' }}">
                            <span class="me-2">
                                @if($done)
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @elseif($lessonItem->id === $lesson->id)
                                    <i class="bi bi-play-circle-fill text-white"></i>
                                @else
                                    <i class="bi bi-circle text-secondary"></i>
                                @endif
                            </span>
                            <span class="small flex-grow-1">{{ $lessonItem->title }}</span>
                            @if($lessonItem->duration)
                                <span class="small opacity-75 ms-2">{{ $lessonItem->duration }}m</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>

        {{-- Main Content --}}
        <div class="flex-grow-1 overflow-auto p-4">

            <h3 class="mb-3">{{ $lesson->title }}</h3>

            {{-- Video --}}
            @if($lesson->type === 'video' && $lesson->video_path)
                <div class="ratio ratio-16x9 mb-4">
                    <video controls class="rounded">
                        <source src="{{ asset('storage/' . $lesson->video_path) }}">
                    </video>
                </div>
            @endif

            {{-- PDF --}}
            @if($lesson->type === 'pdf' && $lesson->pdf_path)
                <div class="mb-4">
                    <a href="{{ asset('storage/' . $lesson->pdf_path) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-file-pdf me-1"></i> View PDF
                    </a>
                </div>
            @endif

            {{-- Article --}}
            @if($lesson->content)
                <div class="mb-4">{!! $lesson->content !!}</div>
            @endif

            {{-- Navigation + Complete --}}
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <div>
                    @if($prevLesson)
                        <a href="{{ route('learn', [$course->id, $prevLesson->id]) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Previous
                        </a>
                    @endif
                </div>

                <div>
                    @if($isCompleted)
                        <button class="btn btn-success" disabled>
                            <i class="bi bi-check-circle-fill me-1"></i> Completed
                        </button>
                    @else
                        <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check2 me-1"></i> Mark as Complete
                            </button>
                        </form>
                    @endif
                </div>

                <div>
                    @if($nextLesson)
                        <a href="{{ route('learn', [$course->id, $nextLesson->id]) }}" class="btn btn-outline-primary">
                            Next <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
