@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-3">All Courses</h2>

    {{-- Search + Filters في الأعلى --}}
    <form method="GET" action="{{ route('courses.index') }}" class="mb-4">
        <div class="row g-2 align-items-end">

            {{-- Search --}}
            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by title or instructor..."
                       value="{{ request('search') }}">
            </div>

            {{-- Category --}}
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->courses_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Level --}}
            <div class="col-md-2">
                <select name="level" class="form-select">
                    <option value="">All Levels</option>
                    <option value="beginner"     {{ request('level') == 'beginner'     ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced"     {{ request('level') == 'advanced'     ? 'selected' : '' }}>Advanced</option>
                </select>
            </div>

            {{-- Sort --}}
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="newest"     {{ request('sort','newest') == 'newest'     ? 'selected' : '' }}>Newest</option>
                    <option value="popular"    {{ request('sort') == 'popular'    ? 'selected' : '' }}>Most Popular</option>
                    <option value="price_low"  {{ request('sort') == 'price_low'  ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </div>

            {{-- Free Only + Buttons --}}
            <div class="col-md-2 d-flex gap-2 align-items-center">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="free_only"
                           id="free_only" {{ request('free_only') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="free_only">Free</label>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x"></i>
                </a>
            </div>

        </div>
    </form>

    {{-- Results Count --}}
    <p class="text-muted small mb-3">
        Showing <strong>{{ $courses->total() }}</strong> courses
    </p>

    {{-- Cards --}}
    <div class="row">
        @forelse($courses as $course)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">

                    {{-- Thumbnail --}}
                    @if($course->image)
                        <img src="{{ asset('storage/' . $course->image) }}"
                             class="card-img-top" style="height:160px; object-fit:cover;"
                             alt="{{ $course->title }}">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center
                                    justify-content-center" style="height:160px;">
                            <i class="bi bi-journal-play fs-1"></i>
                        </div>
                    @endif

                    <div class="card-body">

                        {{-- Badges --}}
                        <div class="d-flex gap-1 mb-2">
                            <span class="badge bg-light text-dark border">{{ $course->category->name ?? '—' }}</span>
                            <span class="badge
                                {{ $course->level == 'beginner' ? 'bg-success' :
                                   ($course->level == 'intermediate' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ ucfirst($course->level) }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h6 class="card-title fw-bold mb-1">{{ $course->title }}</h6>

                        {{-- Instructor --}}
                        <p class="text-muted small mb-2">
                            <i class="bi bi-person me-1"></i>{{ $course->instructor->name ?? '—' }}
                        </p>

                        {{-- Short Description --}}
                        <p class="card-text text-muted small mb-2"
                           style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                            {{ $course->short_description }}
                        </p>

                        {{-- Rating + Students --}}
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="text-warning small">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($course->rating) ? '-fill' : '' }}"></i>
                                @endfor
                            </span>
                            <span class="small text-muted">({{ number_format($course->rating, 1) }})</span>
                            <span class="small text-muted ms-1">
                                <i class="bi bi-people me-1"></i>{{ $course->enrollments_count ?? 0 }} students
                            </span>
                        </div>

                        {{-- Duration --}}
                        <p class="small text-muted mb-0">
                            <i class="bi bi-clock me-1"></i>{{ $course->duration_hours }}h {{ $course->duration_minutes }}m
                        </p>

                    </div>

                    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary fs-5">
                            {{ $course->price == 0 ? 'Free' : '$' . $course->price }}
                        </span>
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-sm">
                            View Details
                        </a>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                No courses found.
                <a href="{{ route('courses.index') }}" class="d-block mt-2">Clear filters</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-2">
        {{ $courses->links() }}
    </div>

</div>
@endsection
