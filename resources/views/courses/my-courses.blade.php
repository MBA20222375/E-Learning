@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4"><i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>My Courses</h2>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" id="courseTabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#all">
                All <span class="badge bg-secondary ms-1">{{ $enrollments->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#inprogress">
                In Progress <span class="badge bg-warning text-dark ms-1">{{ $enrollments->where('status', 'active')->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#completed">
                Completed <span class="badge bg-success ms-1">{{ $enrollments->where('status', 'completed')->count() }}</span>
            </a>
        </li>
    </ul>

    <div class="tab-content">

        {{-- All --}}
        <div class="tab-pane fade show active" id="all">
            @include('courses._enrollment-cards', ['items' => $enrollments])
        </div>

        {{-- In Progress --}}
        <div class="tab-pane fade" id="inprogress">
            @include('courses._enrollment-cards', ['items' => $enrollments->where('status', 'active')])
        </div>

        {{-- Completed --}}
        <div class="tab-pane fade" id="completed">
            @include('courses._enrollment-cards', ['items' => $enrollments->where('status', 'completed')])
        </div>

    </div>
</div>
@endsection
