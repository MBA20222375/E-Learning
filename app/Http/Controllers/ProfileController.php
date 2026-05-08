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
    // ✅ عرض الصفحة
   public function index()
{
    $user = Auth::user();

    // ✅ Stats مؤقتة — هتتحدث لما نعمل الـ Courses system
    $data = [
        'enrolledCourses'  => 0,
        'completedCourses' => 0,
        'certificates'     => 0,
        'totalCourses'     => 0,
        'totalStudents'    => 0,
    ];

    return view('profile.index', $data);
}

    // ✅ تحديث البيانات
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name'      => 'required|string|min:3|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'phone'     => 'nullable|digits:10|unique:users,phone,' . $user->id,
            'about' => 'nullable|string|max:500',                    // max 500 chars
            'address'   => 'nullable|string|max:255',
            'twitter'   => 'nullable|url|max:255',
            'facebook'  => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin'  => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // ✅ رفع صورة جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $user->image = $request->file('image')->store('profiles', 'public');
        }

        $user->name      = $request->name;
        $user->email     = $request->email;
        $user->phone     = $request->phone;
        $user->about     = $request->about;
        $user->address   = $request->address;
        $user->twitter   = $request->twitter;
        $user->facebook  = $request->facebook;
        $user->instagram = $request->instagram;
        $user->linkedin  = $request->linkedin;
        $user->save();

        return back()->with('success', 'Profile updated successfully! ✅');
    }

    // ✅ تغيير الباسورد
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

        // ✅ التحقق من الباسورد الحالي
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
