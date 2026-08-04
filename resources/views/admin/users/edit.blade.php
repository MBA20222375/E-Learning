@extends('layouts.app')
@section('title', 'Edit User')

@section('content')

<style>
    #status:checked ~ label .badge.bg-danger { display: none; }
    #status:not(:checked) ~ label .badge.bg-success { display: none; }
</style>

<div class="pagetitle">
    <h1>Edit User</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body pt-4">
                    <h5 class="card-title">Edit User — {{ $user->name }}</h5>

                    <form action="{{ route('admin.users.update', $user) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Profile Image --}}
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                            <div class="col-md-8 col-lg-9">
                                <img id="profilePreview"
                                     src="{{ $user->image ? asset('storage/' . $user->image) : asset('assets/img/profile-img.jpg') }}"
                                     alt="Profile"
                                     style="width:80px;height:80px;object-fit:cover;border-radius:50%;border:3px solid #4154f1;">
                                <div class="pt-2">
                                    <label for="imageUpload" class="btn btn-primary btn-sm">
                                        <i class="bi bi-upload"></i> Upload
                                    </label>
                                    <input type="file" name="image" id="imageUpload"
                                           class="d-none" accept="image/*">
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
                                       value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Email</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Phone</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="phone" type="text"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Role</label>
                            <div class="col-md-8 col-lg-9">
                                <select name="role" class="form-select @error('role') is-invalid @enderror">
                                    <option value="student"    {{ old('role', $user->role) === 'student'    ? 'selected' : '' }}>Student</option>
                                    <option value="instructor" {{ old('role', $user->role) === 'instructor' ? 'selected' : '' }}>Instructor</option>
                                    <option value="admin"      {{ old('role', $user->role) === 'admin'      ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Account Status --}}
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Account Status</label>
                            <div class="col-md-8 col-lg-9 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                           name="status" id="status" value="1"
                                           {{ old('status', $user->status) ? 'checked' : '' }}
                                           style="width:3rem;height:1.5rem;cursor:pointer">
                                    <label class="form-check-label ms-2" for="status">
                                        <span class="badge bg-success">Active</span>
                                        <span class="badge bg-danger">Inactive</span>
                                    </label>
                                </div>
                            </div>
                        </div>

</div>

                        {{-- Buttons --}}
                        <div class="row mb-3">
                            <div class="col-md-8 col-lg-9 offset-md-4 offset-lg-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Save Changes
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Toast --}}
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    @if (session('success'))
        <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="toast align-items-center text-white bg-warning border-0" role="alert" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
</div>

@endsection
