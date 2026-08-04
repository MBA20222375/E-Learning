<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
{
    $request->validate([
        'name'     => 'required|string|min:3|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'phone'    => 'required|digits:11|unique:users,phone',
        'role'     => 'nullable|in:student,instructor',
        'image'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'experience' => 'nullable|string|max:255',
    ]);
    $user = User::create([
        'name'       => $request->name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'phone'      => $request->phone,
        'role'       => $request->role ?? 'student',
        'experience' => $request->experience,
        'image'      => $request->hasFile('image')
    ? $request->file('image')->store('profile_images', 'public')
  : null,
    ]);

    Auth::login($user);
    $user->update(['last_login_at' => now()]);

    return $this->redirectByRole($user, 'Account created successfully!');
}
public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user->status) {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account has been disabled.']);
        }
        $user->update(['last_login_at' => now()]);

        return $this->redirectByRole($user, 'Welcome ' . $user->name);
    }

    return back()->withErrors([
        'email' => 'Incorrect email or password.'
    ])->withInput($request->only('email', 'remember'));
}

private function redirectByRole(User $user, string $message)
{
    $route = match($user->role) {
        'admin'      => 'admin.dashboard',
        'instructor' => 'instructor.dashboard',
        default      => 'dashboard',
    };

    return redirect()->route($route)->with('success', $message);
}
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'Logged out successfully!');
}
public function dashboard()
{
    $user = Auth::user();

    $enrollments = $user->enrollments()
                        ->with('course.instructor')
                        ->latest()
                        ->get();

    return view('student.dashboard', [
        'enrollments'      => $enrollments,
        'enrolledCourses'  => $enrollments->count(),
        'completedCourses' => $enrollments->where('progress', 100)->count(),
        'certificates'     => 0,
    ]);
}
}
