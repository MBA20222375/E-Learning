<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\User;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
         return view('admin.dashboard', [
        'totalCourses' => Course::query()->count(),
        'totalUsers'   => User::query()->count(),
        'recentUsers'  => User::latest()->take(5)->get(),
    ]);
    }
}
