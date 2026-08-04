@extends('layouts.app')
@section('title', 'Add New Lesson')

@section('content')
<div class="pagetitle">
    <h1>Add New Lesson</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('courses.content', $course) }}">Manage Content</a></li>
            <li class="breadcrumb-item active">Add Lesson</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body pt-4">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('lessons.store', [$course, $section]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="alert alert-secondary py-2">
                             Adding lesson to section: <strong>{{ $section->title }}</strong>
                        </div>

                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label">Lesson Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}" placeholder="e.g. 1.1 Introduction to Laravel" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            {{-- Lesson Type --}}
                            <div class="col-md-6">
                                <label class="form-label">Lesson Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>🎥 Video Lesson</option>
                                    <option value="article" {{ old('type') == 'article' ? 'selected' : '' }}> Article Lesson (Text)</option>
                                </select>
                                <div class="form-text text-muted">Fill the matching fields below based on your choice.</div>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Video Source --}}
                            <div class="col-md-6">
                                <label class="form-label">Video Source <span class="text-muted">(If Video Type)</span></label>
                                <select name="video_source" class="form-select @error('video_source') is-invalid @enderror">
                                    <option value="">-- Select Source --</option>
                                    <option value="upload" {{ old('video_source') == 'upload' ? 'selected' : '' }}>Direct Upload (.mp4, .mov)</option>
                                    <option value="youtube" {{ old('video_source') == 'youtube' ? 'selected' : '' }}>YouTube URL</option>
                                </select>
                                @error('video_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        {{-- Section 1: Video Fields --}}
                        <div class="p-3 bg-light rounded mb-3 border">
                            <h6 class="fw-bold text-primary mb-3">🎬 Video Lesson Details</h6>

                            {{-- Video File --}}
                            <div class="mb-3">
                                <label class="form-label">Upload Video File</label>
                                <input type="file" name="video_file" class="form-control @error('video_file') is-invalid @enderror" accept="video/*">
                                <div class="form-text text-muted">Supported: mp4, mov, ogg, qt — Max: 50MB</div>
                                @error('video_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- YouTube URL --}}
                            <div class="mb-0">
                                <label class="form-label">Or YouTube Link</label>
                                <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror"
                                       value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Section 2: Article Fields --}}
                        <div class="p-3 bg-light rounded mb-3 border">
                            <h6 class="fw-bold text-success mb-3"> Article Lesson Details</h6>

                            {{-- Content Textarea --}}
                            <div class="mb-0">
                                <label class="form-label">Article Content</label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                                          rows="6" placeholder="Write your lesson text, code snippets, or notes here...">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        {{-- Duration --}}
                        <div class="mb-3">
                            <label class="form-label">Duration (Minutes) <span class="text-danger">*</span></label>
                            <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror"
                                   value="{{ old('duration', 10) }}" min="1" required>
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Attachment PDF/ZIP --}}
                        <div class="mb-3">
                            <label class="form-label">Attachment Resource <span class="text-muted">(Optional)</span></label>
                            <input type="file" name="pdf_file" class="form-control @error('pdf_file') is-invalid @enderror">
                            <div class="form-text text-muted">PDF, ZIP, RAR — Max: 10MB</div>
                            @error('pdf_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Is Free Preview --}}
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_free_preview" value="0">
                                <input class="form-check-input" type="checkbox" name="is_free_preview" id="is_free_preview"
                                       value="1" {{ old('is_free_preview') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_free_preview">
                                     Allow Free Preview
                                </label>
                            </div>
                            <div class="form-text text-muted">Students can watch/read this lesson before purchasing the course.</div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i> Save Lesson
                            </button>
                            <a href="{{ route('courses.content', $course) }}" class="btn btn-secondary">Cancel</a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
