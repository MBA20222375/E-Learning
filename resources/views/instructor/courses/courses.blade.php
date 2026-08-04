@extends('layouts.app')
@section('title', 'My Courses')

@section('content')

<div class="pagetitle">
    <h1>My Courses</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">My Courses</li>
        </ol>
    </nav>
</div>

<section class="section">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body pt-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">
                    All Courses
                    <span class="badge bg-primary ms-1">{{ $courses->total() }}</span>
                </h5>
                <a href="{{ route('courses.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> New Course
                </a>
            </div>

            @if($courses->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                    <p class="mb-3">You haven't created any courses yet.</p>
                    <a href="{{ route('courses.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Create Your First Course
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td class="text-muted small">{{ $course->id }}</td>

                                {{-- Title + thumbnail --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($course->image)
                                            <img src="{{ asset('storage/' . $course->image) }}"
                                                 class="rounded"
                                                 style="width:56px;height:38px;object-fit:cover;"
                                                 alt="{{ $course->title }}">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                 style="width:56px;height:38px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ Str::limit($course->title, 35) }}</div>
                                            <small class="text-muted">{{ Str::limit($course->description, 45) }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Category --}}
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $course->category->name ?? '—' }}
                                    </span>
                                </td>

                                {{-- Price --}}
                                <td>
                                    @if($course->price == 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Free</span>
                                    @else
                                        <span class="fw-semibold">${{ number_format($course->price, 2) }}</span>
                                    @endif
                                </td>

                                {{-- Rating --}}
                                <td>
                                    @if($course->rating > 0)
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span class="fw-semibold">{{ number_format($course->rating, 1) }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($course->is_published)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i>Published
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                            <i class="bi bi-pencil me-1"></i>Draft
                                        </span>
                                    @endif
                                </td>

                                {{-- Date --}}
                                <td class="text-muted small">
                                    {{ $course->created_at->format('d M Y') }}
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center flex-nowrap">

                                        {{-- View --}}
                                        <a href="{{ route('courses.show', $course->id) }}"
                                           class="btn btn-sm btn-outline-secondary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('courses.edit', $course->id) }}"
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- Toggle Publish --}}
                                        <form action="{{ route('courses.togglePublish', $course->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm {{ $course->is_published ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                    title="{{ $course->is_published ? 'Unpublish' : 'Publish' }}">
                                                <i class="bi {{ $course->is_published ? 'bi-eye-slash' : 'bi-globe' }}"></i>
                                            </button>
                                        </form>

                                        {{-- Delete --}}
                                        <form action="{{ route('courses.destroy', $course->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this course?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($courses->hasPages())
                    <div class="d-flex justify-content-end mt-3">
                        {{ $courses->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</section>

@endsection
