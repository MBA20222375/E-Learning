<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class LessonUserController extends Controller
{
    public function learn(Course $course, Lesson $lesson)
    {
        $enrollment = Enrollment::where('course_id', $course->id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        $sections = $course->sections()->with('lessons')->orderBy('order')->get();

        $completedLessons = LessonUser::where('user_id', Auth::id())
            ->where('is_completed', true)
            ->pluck('lesson_id')
            ->toArray();

        $isCompleted = in_array($lesson->id, $completedLessons);

        return view('courses.learn', compact(
            'course', 'lesson', 'sections',
            'completedLessons', 'prevLesson', 'nextLesson',
            'enrollment', 'isCompleted'
        ));
    }

public function complete(Lesson $lesson)
{
    $userId = Auth::id();

    LessonUser::updateOrCreate(
        ['user_id' => $userId, 'lesson_id' => $lesson->id],
        ['is_completed' => true]
    );

    $section = $lesson->section;
    $course = $section->course;

    // تحميل كل الـ sections والـ lessons
    $course->load('sections.lessons');

    $allLessons = $course->sections->flatMap->lessons;
    $totalLessons = $allLessons->count();

    $completedCount = LessonUser::where('user_id', $userId)
        ->whereIn('lesson_id', $allLessons->pluck('id'))
        ->where('is_completed', true)
        ->count();

    $progress = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

    $enrollment = Enrollment::where('course_id', $course->id)
        ->where('student_id', $userId)
        ->first();

    $enrollment->update([
        'progress'        => $progress,
        'status'          => $progress >= 100 ? 'completed' : 'active',
        'completion_date' => $progress >= 100 ? now() : null,
    ]);

    if ($progress >= 100) {
        return redirect()->route('my-courses')
            ->with('success', '🎉 Congratulations! You completed the course!');
    }

    // الدرس الجاي من كل الكورس مش بس نفس الـ section
    $currentIndex = $allLessons->search(fn($l) => $l->id === $lesson->id);
    $nextLesson = $allLessons[$currentIndex + 1] ?? null;

    return redirect()->route('learn', [$course->id, $nextLesson ? $nextLesson->id : $lesson->id])
        ->with('success', 'Lesson marked as complete!');
}
}
