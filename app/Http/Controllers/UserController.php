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
            'name'                  => 'required|string|min:3|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
            'phone'                 => 'required|digits:10|unique:users,phone',
            'role'                  => 'nullable|in:student,instructor,admin',
            'image'                 => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'experience'            => 'nullable|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
        }

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'phone'      => $request->phone,
            'role'       => $request->role ?? 'student',
            'experience' => $request->experience,
            'image'      => $imagePath,
        ]);

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Account created successfully!');
        } elseif ($user->role === 'instructor') {
            return redirect()->route('instructor.dashboard')->with('success', 'Account created successfully!');
        } else {
            return redirect()->route('dashboard')->with('success', 'Account created successfully!');
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome  '.$user->name);
            } elseif ($user->role === 'instructor') {
                return redirect()->route('instructor.dashboard')->with('success', 'Welcome  '.$user->name);
            } else {
                return redirect()->route('dashboard')->with('success', 'Welcome  '.$user->name);
            }
        }

        return back()->withErrors([
            'email' => 'Incorrect password or incorrect email address'
        ])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
