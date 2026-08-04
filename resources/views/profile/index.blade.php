@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <section class="section profile">
        <div class="row">

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        {{-- Profile Image --}}
                        @if (Auth::user()->image)
                            <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="Profile" class="rounded-circle"
                                style="width:120px;height:120px;object-fit:cover;">
                        @else
                            <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle"
                                style="width:120px;height:120px;object-fit:cover;">
                        @endif

                        <h2 class="mt-2">{{ Auth::user()->name }}</h2>
                        <h3 class="text-capitalize">{{ Auth::user()->role }}</h3>


                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">

                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#profile-overview">Overview</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                                    Profile</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#profile-change-password">Change Password</button>
                            </li>
                        </ul>

                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                @if (Auth::user()->bio)
                                    <h5 class="card-title">bio</h5>
                                    <p class="small fst-italic">{{ Auth::user()->bio }}</p>
                                @endif

                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Full Name</div>
                                    <div class="col-lg-9 col-md-8">{{ Auth::user()->name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ Auth::user()->email }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phone</div>
                                    <div class="col-lg-9 col-md-8">{{ Auth::user()->phone ?? '—' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Role</div>
                                    <div class="col-lg-9 col-md-8 text-capitalize">{{ Auth::user()->role }}</div>
                                </div>

                                @if (Auth::user()->experience)
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Experience</div>
                                        <div class="col-lg-9 col-md-8">{{ Auth::user()->experience }}</div>
                                    </div>
                                @endif

                                @if (Auth::user()->address)
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Address</div>
                                        <div class="col-lg-9 col-md-8">{{ Auth::user()->address }}</div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Member Since</div>
                                    <div class="col-lg-9 col-md-8">{{ Auth::user()->created_at->format('d M Y') }}</div>
                                </div>

                                @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'student')
                                    <h5 class="card-title mt-4">Statistics</h5>
                                    <div class="row g-3">

                                        @if (Auth::user()->role === 'instructor')
                                            <div class="col-md-6">
                                                <div class="card border-0 bg-light text-center p-3">
                                                    <h3 class="text-primary fw-bold">{{ $totalCourses ?? 0 }}</h3>
                                                    <p class="mb-0 text-muted small">Courses Created</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-0 bg-light text-center p-3">
                                                    <h3 class="text-success fw-bold">{{ $totalStudents ?? 0 }}</h3>
                                                    <p class="mb-0 text-muted small">Total Students</p>
                                                </div>
                                            </div>
                                        @elseif(Auth::user()->role === 'student')
                                            <div class="col-md-6">
                                                <div class="card border-0 bg-light text-center p-3">
                                                    <h3 class="text-primary fw-bold">{{ $enrolledCourses ?? 0 }}</h3>
                                                    <p class="mb-0 text-muted small">Enrolled Courses</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-0 bg-light text-center p-3">
                                                    <h3 class="text-success fw-bold">{{ $completedCourses ?? 0 }}</h3>
                                                    <p class="mb-0 text-muted small">Completed Courses</p>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                @endif

                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                <form action="{{ route('profile.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    {{-- Profile Image --}}
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                        <div class="col-md-8 col-lg-9">

                                            <img id="profilePreview"
                                                src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('assets/img/profile-img.jpg') }}"
                                                alt="Profile"
                                                style="width:80px;height:80px;object-fit:cover;border-radius:50%;border:3px solid #4154f1;">

                                            <div class="pt-2">
                                                <label for="imageUpload" class="btn btn-primary btn-sm"
                                                    title="Upload new image">
                                                    <i class="bi bi-upload"></i> Upload
                                                </label>
                                                <input type="file" name="image" id="imageUpload" class="d-none"
                                                    accept="image/jpg,image/jpeg,image/png">
                                            </div>

                                            @error('image')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    </div>

                                    {{-- Name --}}
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', Auth::user()->name) }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Bio</label>
                                        <div class="col-md-8 col-lg-9">
                                            <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" style="height:100px"
                                                maxlength="500">{{ old('bio', Auth::user()->bio) }}</textarea>
                                            @error('bio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text text-muted">Max 500 characters</div>
                                        </div>
                                    </div>

                                    {{-- Phone --}}
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="phone" type="text"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                value="{{ old('phone', Auth::user()->phone) }}" maxlength="11">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Address</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="address" type="text"
                                                class="form-control @error('address') is-invalid @enderror"
                                                value="{{ old('address', Auth::user()->address) }}">
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', Auth::user()->email) }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>

                                </form>
                            </div>

                            <div class="tab-pane fade pt-3" id="profile-change-password">

                                <form action="{{ route('profile.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="current_password" type="password"
                                                class="form-control @error('current_password') is-invalid @enderror">
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Confirm New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password_confirmation" type="password" class="form-control">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>

                                </form>
                            </div>
                     </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

@push('scripts')
    <script>
        // ✅ Image Preview
        document.getElementById('imageUpload').addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('profilePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
