@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')

    <div class="pagetitle">
        <h1>Manage Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Admin</li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">All Users</h5>

                        {{-- Search & Filter --}}
                        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by name or email..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <select name="role" class="form-select">
                                    <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles
                                    </option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="instructor" {{ request('role') == 'instructor' ? 'selected' : '' }}>
                                        Instructor</option>
                                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-x-lg"></i> Reset
                                </a>
                            </div>
                        </form>

                        {{-- Table --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            {{-- Profile Picture --}}
                                            <td>
                                                @if ($user->image)
                                                    <img src="{{ asset('storage/' . $user->image) }}" class="rounded-circle"
                                                        width="40" height="40" style="object-fit:cover">
                                                @else
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                                        style="width:40px;height:40px">
                                                        <i class="bi bi-person-fill text-white"></i>
                                                    </div>
                                                @endif
                                            </td>

                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>

                                            {{-- Role Badge --}}
                                            <td>
                                                <span
                                                    class="badge
                                                    @if ($user->role === 'admin') bg-danger
                                                    @elseif($user->role === 'instructor') bg-warning text-dark
                                                    @else bg-primary @endif">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>

                                            <td>{{ $user->created_at->format('d M Y') }}</td>
                                            <td>
                                                @if ($user->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                                        class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                No users found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }}
                                of {{ $users->total() }} users
                            </small>
                            {{ $users->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
