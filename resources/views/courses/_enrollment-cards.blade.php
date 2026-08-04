@forelse($items as $enrollment)
    @php $course = $enrollment->course; @endphp
    <div class="card mb-3 shadow-sm">
        <div class="row g-0">

            {{-- Thumbnail --}}
            <div class="col-md-3">
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}"
                         class="img-fluid rounded-start h-100 object-fit-cover" alt="{{ $course->title }}">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center h-100 rounded-start" style="min-height:140px">
                        <i class="bi bi-image" style="font-size:2rem"></i>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="col-md-9">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-1">{{ $course->title }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-person me-1"></i>{{ $course->instructor->name ?? '—' }}
                            </p>
                        </div>
                        {{-- Status Badge --}}
                        @if($enrollment->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                        @else
                            <span class="badge bg-warning text-dark">In Progress</span>
                        @endif
                    </div>

                    {{-- Progress Bar --}}
                    <div class="mb-2">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Progress</span>
                            <span>{{ $enrollment->progress }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ $enrollment->progress }}%"></div>
                        </div>
                    </div>

                    <p class="text-muted small mb-3">
                        <i class="bi bi-clock me-1"></i>Enrolled: {{ $enrollment->enrolled_at?->format('M d, Y') ?? '—' }}
                    </p>

                    {{-- Buttons --}}
                    <div class="d-flex gap-2">
                        <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-play-fill me-1"></i>
                            {{ $enrollment->status === 'completed' ? 'Review Course' : 'Continue Learning' }}
                        </a>
                        @if($enrollment->status === 'completed')
                            <a href="#" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-award me-1"></i> Certificate
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-3">You haven't enrolled in any courses yet.</p>
        <a href="{{ route('courses.index') }}" class="btn btn-primary mt-2">
            <i class="bi bi-search me-1"></i> Browse Courses
        </a>
    </div>
@endforelse
