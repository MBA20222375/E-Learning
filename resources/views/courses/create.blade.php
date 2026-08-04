@extends('layouts.app')
@section('title', 'Create Course')

@section('content')

    <div class="pagetitle">
        <h1>Create New Course</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Course</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body pt-4">

                        <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Title --}}
                            <div class="mb-3">
                                <label class="form-label">Course Title <span class="text-danger">*</span></label>
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                    placeholder="e.g. Complete Web Development Bootcamp">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Short Description --}}
                            <div class="mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="2"
                                    maxlength="200" placeholder="Brief summary of your course (max 200 chars)...">{{ old('short_description') }}</textarea>
                                <div class="form-text text-muted">Max 200 characters</div>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Full Description --}}
                            <div class="mb-3">
                                <label class="form-label">Full Description <span class="text-danger">*</span></label>
                                <div id="descriptionEditor" style="height: 250px;"></div>
                                <input type="hidden" name="description" id="descriptionInput">
                                @error('description')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- What You'll Learn --}}
                            <div class="mb-3">
                                <label class="form-label">What You'll Learn</label>
                                <textarea name="what_you_learn" class="form-control @error('what_you_learn') is-invalid @enderror" rows="5"
                                    placeholder="كل سطر = point واحد...
Build real-world projects
Understand core concepts
Deploy applications">{{ old('what_you_learn') }}</textarea>
                                <div class="form-text text-muted">كل سطر = point واحد</div>
                                @error('what_you_learn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Requirements --}}
                            <div class="mb-3">
                                <label class="form-label">Requirements</label>
                                <textarea name="requirements" class="form-control @error('requirements') is-invalid @enderror" rows="4"
                                    placeholder="كل سطر = requirement واحد...
Basic knowledge of HTML
A computer with internet access">{{ old('requirements') }}</textarea>
                                <div class="form-text text-muted"> </div>
                                @error('requirements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Who Is This For --}}
                            <div class="mb-3">
                                <label class="form-label">Who Is This For</label>
                                <textarea name="who_is_this_for" class="form-control @error('who_is_this_for') is-invalid @enderror" rows="4"
                                    placeholder="كل سطر = نوع واحد من الطلاب...
Beginners who want to learn web development
Students looking to build real projects">{{ old('who_is_this_for') }}</textarea>
                                <div class="form-text text-muted"> </div>
                                @error('who_is_this_for')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Difficulty Level --}}
                            <div class="mb-3">
                                <label class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                                <select name="level" class="form-select @error('level') is-invalid @enderror">
                                    <option value="">-- Select Level --</option>
                                    <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>
                                        Beginner</option>
                                    <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>
                                        Intermediate</option>
                                    <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>
                                        Advanced</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Language --}}
<div class="mb-3">
    <label class="form-label">Language</label>
    <input type="text" name="language"
           class="form-control @error('language') is-invalid @enderror"
           value="{{ old('language', 'English') }}"
           placeholder="e.g. English">
    @error('language')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                            {{-- Price + Category --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                                    <input type="number" name="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price', 0) }}" min="0" step="0.01">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror">
                                        <option value="">-- Select Category --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- Duration --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Duration Hours</label>
                                    <input type="number" name="duration_hours"
                                        class="form-control @error('duration_hours') is-invalid @enderror"
                                        value="{{ old('duration_hours', 0) }}" min="0" max="999">
                                    @error('duration_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Duration Minutes</label>
                                    <input type="number" name="duration_minutes"
                                        class="form-control @error('duration_minutes') is-invalid @enderror"
                                        value="{{ old('duration_minutes', 0) }}" min="0" max="59">
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Image --}}
                            <div class="mb-3">
                                <label class="form-label">Course Image</label>
                                <div>
                                    <img id="imagePreview" src="{{ asset('assets/img/profile-img.jpg') }}"
                                        alt="Preview"
                                        style="width:120px;height:80px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;">
                                </div>
                                <div class="pt-2">
                                    <label for="imageUpload" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-upload me-1"></i> Upload Image
                                    </label>
                                    <input type="file" name="image" id="imageUpload"
                                        class="d-none @error('image') is-invalid @enderror"
                                        accept="image/jpg,image/jpeg,image/png">
                                </div>
                                @error('image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">JPG, PNG — Max 2MB</div>
                            </div>

                            {{-- is_published --}}
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_published"
                                        id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        Publish immediately
                                    </label>
                                </div>
                                <div class="form-text text-muted">
                                    If unchecked, the course will be saved as draft.
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Create Course
                                </button>
                                <a href="{{ route('instructor.dashboard') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

       

        </div>
    </section>

@endsection

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        var quill = new Quill('#descriptionEditor', {
            theme: 'snow',
            placeholder: 'Describe what students will learn...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        @if (old('description'))
            quill.root.innerHTML = {!! json_encode(old('description')) !!};
        @endif

        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('descriptionInput').value = quill.root.innerHTML.trim();
        });

        document.getElementById('imageUpload').addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('imagePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
