<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/register',  fn() => view('auth.register'))->name('register');
Route::post('/register', [UserController::class, 'register']);

Route::get('/login',     fn() => view('auth.login'))->name('login');
Route::post('/login',    [UserController::class, 'login'])->middleware('throttle:5,1');

Route::post('/logout',   [UserController::class, 'logout'])->name('logout');

// Student Dashboard
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', fn() => view('student.dashboard'))->name('dashboard');
});

// Instructor Dashboard
Route::middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('/instructor/dashboard', fn() => view('instructor.dashboard'))->name('instructor.dashboard');
});

// Admin Dashboard
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile',          [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update',   [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
