<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function enroll(Course $course): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to enroll.');
        }

        if (Auth::user()->role === 'instructor' && $course->instructor_id === Auth::id()) {
            return back()->with('error', 'You cannot enroll in your own course.');
        }

        $already = Enrollment::where('course_id', $course->id)
                             ->where('student_id', Auth::id())
                             ->exists();

        if ($already) {
            return back()->with('info', 'You are already enrolled in this course.');
        }

        Enrollment::create([
            'course_id'   => $course->id,
            'student_id'  => Auth::id(),
            'enrolled_at' => now(),
            'progress'    => 0,
            'status'      => 'active',
        ]);

        return redirect()->route('courses.show', $course->id)
                         ->with('success', "Successfully enrolled in {$course->title}!");
    }
    public function myCourses()
{
    $enrollments = Enrollment::with('course.instructor', 'course.sections.lessons')
        ->where('student_id', Auth::id())
        ->latest()
        ->get();

    return view('courses.my-courses', compact('enrollments'));
}
}
