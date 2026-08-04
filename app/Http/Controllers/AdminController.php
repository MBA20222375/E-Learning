<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ─── Dashboard ────────────────────────────────────────────────────────────

    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalCourses'      => Course::count(),
            'totalUsers'        => User::count(),
            'totalEnrollments'  => Enrollment::count(),
            'recentUsers'       => User::latest()->take(10)->get(),
            'recentEnrollments' => Enrollment::with(['student', 'course'])->latest()->take(10)->get(),
        ]);
    }

    // ─── Courses & Categories ─────────────────────────────────────────────────

    public function courses()
    {
        $courses = Course::latest()->paginate(15);
        return view('admin.courses.index', compact('courses'));
    }

    public function categories()
    {
        return view('admin.categories.index');
    }

    // ─── Create Admin ─────────────────────────────────────────────────────────

    public function createAdminForm()
    {
        return view('admin.admins.create');
    }

    public function createAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|min:3|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|digits:11|unique:users,phone',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'phone'    => $request->phone,
            'image'    => $request->hasFile('image')
                            ? $request->file('image')->store('profile_images', 'public')
                            : null,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Admin account created successfully.');
    }

    // ─── Create Instructor ────────────────────────────────────────────────────

    public function createInstructorForm()
    {
        return view('admin.instructors.create');
    }

    public function createInstructor(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => 'required|string|min:3|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'phone'      => 'required|digits:11|unique:users,phone',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'experience' => 'nullable|string|max:255',
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'phone'      => $request->phone,
            'role'       => 'instructor',
            'experience' => $request->experience,
            'image'      => $request->hasFile('image')
                                ? $request->file('image')->store('profile_images', 'public')
                                : null,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Instructor account created successfully.');
    }

    // ─── Users List ───────────────────────────────────────────────────────────

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->withCount('courses')->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // ─── Show User ────────────────────────────────────────────────────────────

    public function showUser(User $user)
    {
        $user->load([
            'courses' => fn($q) => $q->withCount('enrollments'),
            'enrollments.course',
        ]);

        return view('admin.users.show', compact('user'));
    }


    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        if ($user->id === Auth::id() && $request->role !== 'admin') {
            return back()->with('error', 'You cannot change your own role.');
        }

        $request->validate([
            'name'       => 'required|string|min:3|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone'      => 'nullable|digits:11|unique:users,phone,' . $user->id,
            'role'       => 'required|in:admin,instructor,student',
            'experience' => 'nullable|string|max:255',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image') && $user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->update([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'role'       => $request->role,
            'experience' => $request->experience,
            'status'     => $request->boolean('status'),
            'image'      => $request->hasFile('image')
                                ? $request->file('image')->store('profile_images', 'public')
                                : $user->image,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully.');
    }




    public function deleteUser(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
  if ($user->role === 'admin') {
        return back()->with('error', 'Admin accounts cannot be deleted.');
    }
        if ($user->role === 'student') {
            $user->enrollments()->delete();
        }

        if ($user->role === 'instructor') {
            $user->courses()->delete();
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }

    // ─── Force Delete User ────────────────────────────────────────────────────

    public function forceDeleteUser(Request $request, User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
    if ($user->role === 'admin') {
        return back()->with('error', 'Admin accounts cannot be deleted.');
    }

        if ($user->role === 'instructor') {
            $action = $request->input('action');

            if ($action === 'reassign') {
                $newInstructor = User::where('role', 'instructor')
                                     ->where('id', $request->input('new_instructor_id'))
                                     ->where('id', '!=', $user->id)
                                     ->first();

                if (!$newInstructor) {
                    return back()->with('error', 'Please select a valid instructor.');
                }

                $user->courses()->update(['instructor_id' => $newInstructor->id]);

            } else {
                $user->courses()->delete();
            }
        }

        if ($user->role === 'student') {
            $user->enrollments()->delete();
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }
public function dashboardd()
{
    $instructorId = Auth::id();

    $courses = Course::withCount('enrollments')
                     ->where('instructor_id', $instructorId)
                     ->latest()->get();

    $totalCourses  = $courses->count();
    $totalStudents = $courses->sum('enrollments_count');
    $avgRating     = $courses->avg('rating')
                        ? number_format($courses->avg('rating'), 1) : '—';

    $recentEnrollments = \App\Models\Enrollment::with(['student', 'course'])
        ->whereHas('course', fn($q) => $q->where('instructor_id', $instructorId))
        ->latest()
        ->take(10)
        ->get();

    return view('instructor.dashboard', compact(
        'courses', 'totalCourses', 'totalStudents', 'avgRating', 'recentEnrollments'
    ));
}
public function enrollments(Request $request)
{
    $query = Enrollment::with(['student', 'course.instructor']);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('student', fn($q) =>
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
        );
    }

    if ($request->filled('course_id')) {
        $query->where('course_id', $request->course_id);
    }

    return view('admin.enrollments.index', [
        'enrollments' => $query->latest()->paginate(15)->withQueryString(),
        'courses'     => Course::orderBy('title')->get(),
    ]);
}

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course deleted successfully.');
    }
public function destroyCourse(Course $course)
{
    if ($course->image) {
        \Storage::disk('public')->delete($course->image);
    }
    $course->delete();

    return back()->with('success', 'Course deleted successfully.');
}

public function toggleCourse(Course $course)
{
    $course->update(['is_published' => !$course->is_published]);
    return back()->with('success', 'Course status updated.');
}
}
