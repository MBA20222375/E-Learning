<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
public function index()
{
    $user = Auth::user();
    $data = [];

    if ($user->role === 'instructor') {
        $data['totalCourses']  = $user->courses()->count();
        $data['totalStudents'] = $user->courses()
                                      ->withCount('enrollments')
                                      ->get()
                                      ->sum('enrollments_count');
    } elseif ($user->role === 'student') {
        $data['enrolledCourses']  = $user->enrollments()->count();
        $data['completedCourses'] = $user->enrollments()
                                         ->where('completed', true)->count();
    }

    return view('profile.index', $data);
}

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name'      => 'required|string|min:3|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'phone'     => 'nullable|digits:11|unique:users,phone,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'address'   => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $user->image = $request->file('image')->store('profiles', 'public');
        }

        $user->name      = $request->name;
        $user->email     = $request->email;
        $user->phone     = $request->phone;
        $user->bio       = $request->bio;
        $user->address   = $request->address;
        $user->save();

        return back()->with('success', 'Profile updated successfully! ✅');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Current password is required.',
            'password.required'         => 'New password is required.',
            'password.min'              => 'Password must be at least 8 characters.',
            'password.confirmed'        => 'Passwords do not match.',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password changed successfully! 🔒');
    }

}
