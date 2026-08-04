@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Manage Course Content</h2>
            <p class="text-muted">Course: <strong>{{ $course->title }}</strong></p>
        </div>
        <a href="{{ route('courses.preview', $course) }}" class="btn btn-outline-info">
             Preview Course Mode
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4 border-primary">
        <div class="card-body pt-3">
            <h5 class="card-title"> Add New Section</h5>
            <form action="{{ route('sections.store', $course) }}" method="POST" class="row g-2">
                @csrf
                <div class="col-md-10">
                    <input type="text" name="title" class="form-control" placeholder="e.g. Chapter 1: Introduction" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Save Section</button>
                </div>
            </form>
        </div>
    </div>

    <div id="sections-container">
        @foreach($sections as $section)
            <div class="card mb-3 border-secondary">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"> {{ $section->title }}</h5>
                    <a href="{{ route('lessons.create', [$course, $section]) }}" class="btn btn-sm btn-success">
                         Add Lesson
                    </a>
                </div>
                <div class="card-body bg-light">

                    <ul class="list-group">
                        @forelse($section->lessons as $lesson)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">#{{ $lesson->order }}</span> -
                                    <strong>{{ $lesson->title }}</strong>
                                    <span class="badge bg-secondary ms-2">{{ $lesson->type }}</span>
                                    @if($lesson->is_free_preview)
                                        <span class="badge bg-warning text-dark ms-1"> Free Preview</span>
                                    @endif
                                </div>

                                <div class="d-flex gap-1">
                                    <form action="{{ route('lessons.destroy', $lesson) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                             Delete
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center">No lessons added to this section yet.</li>
                        @endforelse
                    </ul>

                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
