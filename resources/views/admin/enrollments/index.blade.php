@extends('layouts.app')
@section('title', 'Course Enrollments')

@section('content')

<div class="pagetitle">
    <h1>Course Enrollments</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Enrollments</li>
        </ol>
    </nav>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                        <h5 class="card-title mb-0">All Enrollments</h5>
                        <span class="badge bg-primary fs-6">{{ $enrollments->total() }} Total</span>
                    </div>

                    {{-- ✅ Search & Filter --}}
                    <form method="GET" action="{{ route('admin.enrollments.index') }}" class="row g-2 mb-3">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Search by student name or email..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="course_id" class="form-select">
                                <option value="">-- All Courses --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('admin.enrollments.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x"></i>
                            </a>
                        </div>
                    </form>

                    {{-- ✅ Table --}}
                    <table class="table table-hover table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Instructor</th>
                                <th>Enrolled At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($enrollment->student?->image)
                                                <img src="{{ asset('storage/' . $enrollment->student->image) }}"
                                                     class="rounded-circle"
                                                     style="width:32px;height:32px;object-fit:cover;">
                                            @else
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                                                     style="width:32px;height:32px;font-size:13px;">
                                                    {{ strtoupper(substr($enrollment->student?->name ?? 'U', 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="fw-bold">{{ $enrollment->student?->name ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $enrollment->student?->email ?? '—' }}</td>
                                    <td>
                                        <span class="fw-bold text-primary">
                                            {{ $enrollment->course?->title ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        {{ $enrollment->course?->instructor?->name ?? '—' }}
                                    </td>
                                    <td class="text-muted">
                                        {{ $enrollment->enrolled_at?->format('d M Y') ?? $enrollment->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                        No enrollments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $enrollments->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
