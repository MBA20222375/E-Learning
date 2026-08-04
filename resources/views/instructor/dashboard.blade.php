@extends('layouts.app')
@section('title', 'Instructor Dashboard')

@section('content')

<div class="pagetitle">
    <h1>Instructor Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<div class="alert alert-light border mb-4">
    Welcome back, <strong>{{ Auth::user()->name }}</strong> 👋
</div>

<section class="section dashboard">

    {{-- ══════════════════════════
         Stats Cards
    ══════════════════════════ --}}
    <div class="row mb-4">

        {{-- Total Courses --}}
        <div class="col-md-4">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Total Courses</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-collection-play"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $totalCourses ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Students --}}
        <div class="col-md-4">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $totalStudents ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Avg Rating --}}
        <div class="col-md-4">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Avg. Rating</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-star"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $avgRating ?? '—' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════
         My Courses Table
    ══════════════════════════ --}}
 {{-- ══════════════════════════
         My Courses Table
         ══════════════════════════ --}}
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">My Courses</h5>
                <a href="{{ route('courses.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Create New Course
                </a>
            </div>

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Course Title</th>
                        <th class="text-center">Students</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses ?? [] as $course)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $course->title }}</div>
                                <small class="text-muted">Level: {{ ucfirst($course->level) }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary border">
                                    {{ $course->enrollments_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                @if($course->is_published)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="bi bi-check-circle me-1"></i>Published
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        <i class="bi bi-file-earmark-medical me-1"></i>Draft
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('courses.content', $course->id) }}" class="btn btn-sm btn-primary" title="Manage Sections & Lessons">
                                        <i class="bi bi-journal-album me-1"></i> Content
                                    </a>

                                    <a href="{{ route('courses.preview', $course->id) }}" class="btn btn-sm btn-outline-info" title="Preview Course">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-outline-warning" title="Edit Course Info">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-collection-play fs-1 d-block mb-2"></i>
                                No courses yet.
                                <a href="{{ route('courses.create') }}" class="d-block mt-2">Create your first course</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    {{-- ══════════════════════════
         Recent Enrollments
    ══════════════════════════ --}}
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="bi bi-people me-1"></i> Recently Enrolled Students
            </h5>

            @if($recentEnrollments->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                    No students enrolled yet.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Enrolled At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentEnrollments as $enrollment)
                            <tr>
                                <td class="text-muted small">{{ $loop->iteration }}</td>

                                {{-- Student --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                             style="width:34px;height:34px;font-size:14px;">
                                            {{ strtoupper(substr($enrollment->student->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $enrollment->student->name ?? '—' }}</div>
                                            <small class="text-muted">{{ $enrollment->student->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Course --}}
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ Str::limit($enrollment->course->title ?? '—', 30) }}
                                    </span>
                                </td>

                                {{-- Date --}}
                                <td class="text-muted small">
                                    {{ $enrollment->created_at->format('d M Y') }}
                                </td>

                                {{-- Status --}}
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="bi bi-check-circle me-1"></i>Enrolled
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>

</section>

@endsection
