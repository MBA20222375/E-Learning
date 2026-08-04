@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')

<div class="pagetitle">
    <h1>Student Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

{{-- ✅ Welcome Message --}}
<div class="alert alert-light border mb-4">
    Welcome back, <strong>{{ Auth::user()->name }}</strong> 👋
</div>

<section class="section dashboard">
    <div class="row">

        {{-- ══════════════════════════
             Stats Cards
        ══════════════════════════ --}}

        {{-- Enrolled Courses --}}
        <div class="col-xxl-4 col-md-4">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Enrolled Courses</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-collection-play"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $enrolledCourses ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Completed Courses --}}
        <div class="col-xxl-4 col-md-4">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Completed Courses</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-patch-check"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $completedCourses ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Certificates --}}
        <div class="col-xxl-4 col-md-4">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Certificates</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-award"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $certificates ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════
             Continue Learning
        ══════════════════════════ --}}
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Continue Learning</h5>
                        <a href="{{ route('courses.index') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-compass me-1"></i> Browse Courses
                        </a>
                    </div>

                    @forelse($enrollments ?? [] as $enrollment)
                        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">

                            {{-- Course Image --}}
                            <img src="{{ $enrollment->course->image ? asset('storage/' . $enrollment->course->image) : asset('assets/img/profile-img.jpg') }}"
                                 alt="{{ $enrollment->course->title }}"
                                 style="width:60px;height:60px;object-fit:cover;border-radius:8px;"
                                 class="me-3">

                            {{-- Course Info --}}
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $enrollment->course->title }}</h6>
                                <small class="text-muted">
                                    {{ $enrollment->course->instructor->name ?? '—' }}
                                </small>

                                {{-- Progress Bar --}}
                                <div class="progress mt-2" style="height:6px;">
                                    <div class="progress-bar bg-primary"
                                         role="progressbar"
                                         style="width: {{ $enrollment->progress ?? 0 }}%"
                                         aria-valuenow="{{ $enrollment->progress ?? 0 }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">{{ $enrollment->progress ?? 0 }}% Complete</small>
                            </div>

                            {{-- Continue Button --}}
                            <div class="ms-3">
                                @if(($enrollment->progress ?? 0) >= 100)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <a href="{{ route('courses.show', $enrollment->course->id) }}" class="btn btn-outline-primary btn-sm">
                                        Continue
                                    </a>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-collection-play fs-1 d-block mb-2"></i>
                            You haven't enrolled in any courses yet.
                            <a href="{{ route('courses.index') }}" class="d-block mt-2">Browse Courses</a>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

    </div>
</section>

@endsection
