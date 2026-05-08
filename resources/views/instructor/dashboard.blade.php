@extends('layouts.app')
@section('title', 'Instructor Dashboard')

@section('content')
<div class="pagetitle">
    <h1>Instructor Dashboard</h1>
</div>

<section class="section dashboard">

    {{-- ✅ Welcome --}}
    <div class="alert alert-success d-flex align-items-center">
        <i class="bi bi-person-workspace me-2 fs-4"></i>
        <span>Welcome, <strong>{{ Auth::user()->name }}</strong>! 👨‍🏫 Manage your courses below.</span>
    </div>

    {{-- ✅ Stats --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Total Courses</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-collection-play"></i>
                        </div>
                        <div class="ps-3"><h6>{{ $totalCourses ?? 0 }}</h6></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ps-3"><h6>{{ $totalStudents ?? 0 }}</h6></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Avg. Rating</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-star"></i>
                        </div>
                        <div class="ps-3"><h6>{{ $avgRating ?? '—' }}</h6></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ My Courses Table --}}
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">My Courses</h5>
                <a href="#" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Create New Course
                </a>
            </div>

            <table class="table table-borderless mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course Title</th>
                        <th>Students</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Placeholder --}}
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-collection-play fs-1 d-block mb-2"></i>
                            No courses yet.
                            <a href="#" class="d-block mt-2">Create your first course</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</section>
@endsection
