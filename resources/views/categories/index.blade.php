@extends('layouts.app')
@section('title', 'Browse Categories')

@section('content')

<div class="pagetitle">
    <h1>Browse Categories</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
            <li class="breadcrumb-item active">Categories</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        @forelse($categories as $category)
            <div class="col-md-4 col-lg-3 mb-4">
                <a href="{{ route('courses.index', ['category' => $category->id]) }}"
                   class="text-decoration-none">
                    <div class="card h-100 text-center p-3 hover-shadow">
                        <div class="card-body">
                            <i class="bi bi-grid fs-1 text-primary mb-3 d-block"></i>
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="text-muted small">{{ $category->description ?? '—' }}</p>
                            <span class="badge bg-primary">
                                {{ $category->courses_count }} courses
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-tags fs-1 d-block mb-2"></i>
                No categories found.
            </div>
        @endforelse
    </div>
</section>

@endsection
