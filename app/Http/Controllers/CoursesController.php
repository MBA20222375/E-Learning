<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CoursesController extends Controller
{
    // ─── Index ────────────────────────────────────────────────────────────────
public function index(Request $request)
{
    $categories = Category::withCount('courses')->orderBy('name')->get();

    $query = Course::with(['category', 'instructor'])->where('is_published', 1);

    if ($request->has('category') && $request->category != '') {
        $query->where('category_id', $request->category);
    }

    if ($request->has('level') && $request->level != '') {
        $query->where('level', $request->level);
    }

    if ($request->has('free_only')) {
        $query->where('price', 0);
    }

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhereHas('instructor', function($inst) use ($search) {
                  $inst->where('name', 'like', "%{$search}%");
              });
        });
    }

    $sort = $request->get('sort', 'newest');

    if ($sort == 'price_low') {
        $query->withCount('enrollments')->orderBy('price', 'asc');
    } elseif ($sort == 'price_high') {
        $query->withCount('enrollments')->orderBy('price', 'desc');
    } elseif ($sort == 'popular') {
        $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
    } else {
        $query->withCount('enrollments')->latest();
    }

    $courses = $query->paginate(12)->withQueryString();

    return view('courses.index', compact('courses', 'categories'));
}
    // ─── Create ───────────────────────────────────────────────────────────────
    public function create()
    {
        if (!in_array(Auth::user()->role, ['instructor', 'admin'])) {
            abort(403);
        }

        $categories = Category::orderBy('name')->get();
        return view('courses.create', compact('categories'));
    }

    // ─── Store (Page 1) ───────────────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title'             => 'required|string|min:3|max:255',
            'short_description' => 'nullable|string|max:200',
            'description'       => 'nullable|string',
            'what_you_learn'    => 'nullable|string',
            'requirements'      => 'nullable|string',
            'who_is_this_for'   => 'nullable|string',
            'level'             => 'required|in:beginner,intermediate,advanced',
            'language'          => 'nullable|string|max:100',
            'price'             => 'required|numeric|min:0',
            'category_id'       => 'required|exists:categories,id',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_published'      => 'boolean',
            'duration_hours'    => 'nullable|integer|min:0|max:999',
            'duration_minutes'  => 'nullable|integer|min:0|max:59',
        ]);

        $course = Course::create([
            'title'             => $request->title,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'what_you_learn'    => $request->what_you_learn,
            'requirements'      => $request->requirements,
            'who_is_this_for'   => $request->who_is_this_for,
            'level'             => $request->level,
            'language'          => $request->language ?? 'English',
            'price'             => $request->price,
            'category_id'       => $request->category_id,
            'instructor_id'     => Auth::id(),
            'duration_hours'    => $request->duration_hours ?? 0,
            'duration_minutes'  => $request->duration_minutes ?? 0,
            'is_published'      => $request->boolean('is_published'),
            'image'             => $request->hasFile('image')
                                    ? $request->file('image')->store('courses', 'public')
                                    : null,
        ]);

        return redirect()->route('courses.content', $course->id)
                         ->with('success', 'Course basic info saved! Now complete the details and add your sections/lessons.');
    }

    // ─── Show ─────────────────────────────────────────────────────────────────
 public function show(Course $course)
{
    $course->load(['category', 'instructor', 'sections.lessons']);

    $isEnrolled = false;
    if (auth()->check()) {
        $isEnrolled = $course->enrollments()
            ->where('student_id', auth()->id())
            ->exists();
    }

    $firstLesson = $course->sections->first()?->lessons->first();

    return view('courses.show', compact('course', 'isEnrolled', 'firstLesson'));
}

    // ─── Edit ─────────────────────────────────────────────────────────────────
    public function edit(Course $course): \Illuminate\View\View
    {
        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::orderBy('name')->get();
        $sections   = $course->sections()->withCount('lessons')->get();

        return view('courses.edit', compact('course', 'categories', 'sections'));
    }

    // ─── Update ───────────────────────────────────────────────────────────────
    public function update(Request $request, Course $course): RedirectResponse
    {
        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title'             => 'required|string|min:3|max:255',
            'short_description' => 'nullable|string|max:200',
            'description'       => 'nullable|string',
            'what_you_learn'    => 'nullable|string',
            'requirements'      => 'nullable|string',
            'who_is_this_for'   => 'nullable|string',
            'level'             => 'required|in:beginner,intermediate,advanced',
            'language'          => 'nullable|string|max:100',
            'price'             => 'required|numeric|min:0',
            'category_id'       => 'required|exists:categories,id',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_published'      => 'boolean',
            'duration_hours'    => 'nullable|integer|min:0|max:999',
            'duration_minutes'  => 'nullable|integer|min:0|max:59',
        ]);

        if ($request->hasFile('image') && $course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->update([
            'title'             => $request->title,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'what_you_learn'    => $request->what_you_learn,
            'requirements'      => $request->requirements,
            'who_is_this_for'   => $request->who_is_this_for,
            'level'             => $request->level,
            'language'          => $request->language ?? 'English',
            'price'             => $request->price,
            'category_id'       => $request->category_id,
            'duration_hours'    => $request->duration_hours ?? 0,
            'duration_minutes'  => $request->duration_minutes ?? 0,
            'is_published'      => $request->boolean('is_published'),
            'image'             => $request->hasFile('image')
                                    ? $request->file('image')->store('courses', 'public')
                                    : $course->image,
        ]);

        return redirect()->route('instructor.dashboard')
                         ->with('success', 'Course updated successfully.');
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────
    public function destroy(Course $course): RedirectResponse
    {
        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            abort(403);
        }

        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('instructor.dashboard')
                         ->with('success', 'Course deleted successfully.');
    }

    // ─── Categories Public ────────────────────────────────────────────────────
    public function categories()
    {
        $categories = Category::withCount('courses')
                               ->having('courses_count', '>', 0)
                               ->orderBy('name')
                               ->get();

        return view('categories.index', compact('categories'));
    }

    // ─── Toggle Publish ───────────────────────────────────────────────────────
    public function togglePublish(Course $course): RedirectResponse
    {
        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            abort(403);
        }

        if (!$course->is_published) {
            $hasLessons = $course->sections()->whereHas('lessons')->exists();

            if (!$hasLessons) {
                return redirect()->back()
                                 ->with('error', 'You must add at least 1 lesson before publishing.');
            }
        }

        $course->update([
            'is_published' => !$course->is_published,
        ]);

        $message = $course->is_published ? 'Course published successfully.' : 'Course saved as draft.';

        return redirect()->back()->with('success', $message);
    }

    // ─── Instructor Index ─────────────────────────────────────────────────────
    public function instructorIndex()
    {
        $courses = Course::with('category')
                         ->where('instructor_id', auth()->id())
                         ->latest()
                         ->paginate(10);

        return view('instructor.courses.courses', compact('courses'));
    }

    // ─── Content (Page 2) ─────────────────────────────────────────────────────
    public function content(Course $course)
    {
        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $sections = $course->sections()->with(['lessons' => function($query) {
            $query->orderBy('order');
        }])->withCount('lessons')->get();

        return view('instructor.courses.content', compact('course', 'sections'));
    }

    // ─── Lessons CRUD: Create ────────────────────────────────────────────────
    public function createLesson(Course $course, Section $section)
    {
        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            abort(403);
        }

        return view('instructor.lessons.create', compact('course', 'section'));
    }

    // ─── Lessons CRUD: Store ─────────────────────────────────────────────────
    public function storeLesson(Request $request, Course $course, Section $section)
    {
        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title'           => 'required|string|max:255',
            'type'            => 'required|in:video,article',
            'video_source'    => 'required_if:type,video|in:upload,youtube',
            'video_file'      => 'required_if:video_source,upload|file|mimes:mp4,mov,ogg,qt|max:50000',
            'video_url'       => 'required_if:video_source,youtube|nullable|url',
            'duration'        => 'required|integer|min:1',
            'content'         => 'required_if:type,article|nullable|string',
            'is_free_preview' => 'boolean',
            'pdf_file'        => 'nullable|file|mimes:pdf,zip,rar|max:10240',
        ]);

        $nextOrder = $section->lessons()->max('order') + 1;

        $videoPath = null;
        if ($request->type === 'video') {
            if ($request->video_source === 'upload' && $request->hasFile('video_file')) {
                $videoPath = $request->file('video_file')->store('lessons/videos', 'public');
            } else {
                $videoPath = $request->video_url;
            }
        }

        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('lessons/attachments', 'public');
        }

        $lesson = Lesson::create([
            'section_id'      => $section->id,
            'title'           => $request->title,
            'type'            => $request->type,
            'video_path'      => $videoPath,
            'pdf_path'        => $pdfPath,
            'content'         => $request->content,
            'duration'        => $request->duration,
            'is_free_preview' => $request->boolean('is_free_preview'),
            'order'           => $nextOrder,
        ]);

        if ($request->has('add_another')) {
            return redirect()->route('lessons.create', [$course->id, $section->id])
                             ->with('success', 'Lesson saved! Add another.');
        }

        return redirect()->route('courses.content', $course->id)
                         ->with('success', 'Lesson created successfully.');
    }

    // ─── Lessons CRUD: Destroy ───────────────────────────────────────────────
    public function destroyLesson(Lesson $lesson)
    {
        if (Auth::user()->role === 'instructor' && $lesson->section->course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $courseId = $lesson->section->course_id;

        if ($lesson->video_path && !filter_var($lesson->video_path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($lesson->video_path);
        }
        if ($lesson->pdf_path) {
            Storage::disk('public')->delete($lesson->pdf_path);
        }

        $lesson->delete();
        return redirect()->route('courses.content', $courseId)->with('success', 'Lesson deleted.');
    }

    // ─── Preview Course Mode ──────────────────────────────────────────────────
    public function preview(Course $course)
    {
        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $course->load(['sections.lessons' => function($query) {
            $query->orderBy('order');
        }]);

        return view('instructor.courses.preview', compact('course'));
    }

    // ─── My Students (Instructor Dashboard) ───────────────────────────────────
    public function myStudents()
    {
        $instructorCourses = Course::where('instructor_id', Auth::id())->pluck('id');

        $students = User::whereHas('enrollments', function($query) use ($instructorCourses) {
            $query->whereIn('course_id', $instructorCourses);
        })->paginate(15);

        return view('instructor.students.index', compact('students'));
    }
}
