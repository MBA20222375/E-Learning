@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')

    <div class="pagetitle">
        <h1>Admin Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
{{-- ✅ أضف تحت pagetitle --}}
<p class="text-muted">Welcome back, <strong>{{ Auth::user()->name }}</strong> 👋</p>

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Total Courses Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Total Courses </h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-collection-play"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalCourses }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Revenue </h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="ps-3">
<h6>${{ number_format($totalRevenue ?? 0, 2) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Users Card -->
                    <div class="col-xxl-4 col-xl-12">
                        <div class="card info-card customers-card">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Users</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalUsers }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        {{-- Total Enrollments Card --}}
<div class="col-xxl-3 col-md-6">
    <div class="card info-card" style="border-left: 3px solid #2eca6a;">
        <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-three-dots"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start"><h6>Filter</h6></li>
                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Enrollments</h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                     style="background: rgba(46,202,106,.2); color: #2eca6a;">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div class="ps-3">
                    <h6>{{ $totalEnrollments }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">
                <!-- Quick Links -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quick Links</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.users.index') }}"
                                class="btn btn-outline-primary d-flex align-items-center gap-2">
                                <i class="bi bi-people-fill"></i> Manage Users
                            </a>
                            <a href="{{ route('admin.courses.index') }}"
                                class="btn btn-outline-success d-flex align-items-center gap-2">
                                <i class="bi bi-collection-play-fill"></i> Manage Courses
                            </a>
                            <a href="{{ route('admin.categories.index') }}"
                                class="btn btn-outline-warning d-flex align-items-center gap-2">
                                <i class="bi bi-tags-fill"></i> Manage Categories
                            </a>
                             <a href="{{ route('admin.admins.create') }}"
                                class="btn btn-outline-info d-flex align-items-center gap-2">
                                <i class="bi bi-person-plus-fill"></i> Create Admin Account
                            </a>
                             <a href="{{ route('admin.instructors.create') }}"
                                class="btn btn-outline-info d-flex align-items-center gap-2">
                                <i class="bi bi-person-plus-fill"></i> Create instructor Account
                            </a>
                            <a href="{{ route('admin.enrollments.index') }}"
   class="btn btn-outline-secondary d-flex align-items-center gap-2">
    <i class="bi bi-journal-check"></i> View Enrollments
</a>
                        </div>
                    </div>
                </div>



            </div>

        </div>
    </section>

@endsection
