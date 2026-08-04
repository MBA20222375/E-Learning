@extends('layouts.app')
@section('title', 'User Details')

@section('content')

<div class="pagetitle">
    <h1>User Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
        </ol>
    </nav>
</div>

<section class="section profile">
    <div class="row">

        {{-- Left: Profile Card --}}
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                    @if ($user->image)
                        <img src="{{ asset('storage/' . $user->image) }}" alt="Profile"
                             class="rounded-circle" style="width:120px;height:120px;object-fit:cover;border:3px solid #4154f1">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                             style="width:120px;height:120px">
                            <i class="bi bi-person-fill text-white" style="font-size:3rem"></i>
                        </div>
                    @endif

                    <h2 class="mt-3">{{ $user->name }}</h2>

                    <span class="badge mt-1
                        @if($user->role === 'admin') bg-danger
                        @elseif($user->role === 'instructor') bg-warning text-dark
                        @else bg-primary @endif" style="font-size:.85rem">
                        {{ ucfirst($user->role) }}
                    </span>

                    {{-- Status --}}
                   <p class="mt-2 mb-0">
    @if($user->status)
        <span class="badge bg-success">Active</span>
    @else
        <span class="badge bg-danger">Inactive</span>
    @endif
</p>
                    {{-- Last Login --}}
                    <p class="text-muted small mt-2">
                        <i class="bi bi-clock me-1"></i>
                        Last login:
                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                    </p>

                    {{-- Action Buttons --}}
                    <div class="mt-2 d-flex gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        {{-- Right: Details --}}
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body pt-3">

                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#profile-overview">
                                <i class="bi bi-person me-1"></i> Overview
                            </button>
                        </li>
                        @if($user->role === 'instructor' || $user->role === 'student')
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#profile-activity">
                                <i class="bi bi-activity me-1"></i> Activity
                            </button>
                        </li>
                        @endif
                    </ul>

                    <div class="tab-content pt-3">

                        {{-- Overview Tab --}}
                        <div class="tab-pane fade show active" id="profile-overview">
                            <h5 class="card-title">Profile Details</h5>

                            <div class="row mb-2 py-1 border-bottom">
                                <div class="col-lg-3 col-md-4 fw-bold text-muted">Full Name</div>
                                <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                            </div>
                            <div class="row mb-2 py-1 border-bottom">
                                <div class="col-lg-3 col-md-4 fw-bold text-muted">Email</div>
                                <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                            </div>
                            <div class="row mb-2 py-1 border-bottom">
                                <div class="col-lg-3 col-md-4 fw-bold text-muted">Phone</div>
                                <div class="col-lg-9 col-md-8">{{ $user->phone ?? '—' }}</div>
                            </div>
                            <div class="row mb-2 py-1 border-bottom">
                                <div class="col-lg-3 col-md-4 fw-bold text-muted">Role</div>
                                <div class="col-lg-9 col-md-8">
                                    <span class="badge
                                        @if($user->role === 'admin') bg-danger
                                        @elseif($user->role === 'instructor') bg-warning text-dark
                                        @else bg-primary @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                            </div>
                            @if($user->experience)
                            <div class="row mb-2 py-1 border-bottom">
                                <div class="col-lg-3 col-md-4 fw-bold text-muted">Experience</div>
                                <div class="col-lg-9 col-md-8">{{ $user->experience }}</div>
                            </div>
                            @endif
                            <div class="row mb-2 py-1 border-bottom">
                                <div class="col-lg-3 col-md-4 fw-bold text-muted">Joined</div>
                                <div class="col-lg-9 col-md-8">{{ $user->created_at->format('d M Y') }}</div>
                            </div>
                            <div class="row mb-2 py-1 border-bottom">
                                <div class="col-lg-3 col-md-4 fw-bold text-muted">Last Login</div>
                                <div class="col-lg-9 col-md-8">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d M Y, h:i A') : 'Never' }}
                                </div>
                            </div>
                            <div class="row mb-2 py-1">
                                <div class="col-lg-3 col-md-4 fw-bold text-muted">Status</div>
                                <div class="col-lg-9 col-md-8">
                                            @if($user->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                                </div>
                            </div>
                        </div>

                        {{-- Activity Tab --}}
                        @if($user->role === 'instructor' || $user->role === 'student')
                        <div class="tab-pane fade" id="profile-activity">

                            @if($user->role === 'instructor')
                                <h5 class="card-title">
                                    <i class="bi bi-collection-play me-2"></i>
                                    Courses Created
                                    <span class="badge bg-primary ms-2">{{ $user->courses->count() }}</span>
                                </h5>

                                @if($user->courses->count())
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Course Title</th>
                                                    <th>Students</th>
                                                    <th>Created</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($user->courses as $course)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $course->title }}</td>
                                                    <td>
                                                        <span class="badge bg-info text-dark">
                                                            {{ $course->enrollments_count ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $course->created_at->format('d M Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        No courses created yet.
                                    </div>
                                @endif

                            @elseif($user->role === 'student')
                                <h5 class="card-title">
                                    <i class="bi bi-book me-2"></i>
                                    Enrolled Courses
                                    <span class="badge bg-primary ms-2">{{ $user->enrollments->count() }}</span>
                                </h5>

                                @if($user->enrollments->count())
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Course Title</th>
                                                    <th>Enrolled Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($user->enrollments as $enrollment)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $enrollment->course->title ?? '—' }}</td>
                                                    <td>{{ $enrollment->created_at->format('d M Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        No enrollments yet.
                                    </div>
                                @endif
                            @endif

                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Back Button --}}
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary mt-2">
        <i class="bi bi-arrow-left"></i> Back to Users
    </a>

</section>

@endsection
