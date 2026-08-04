<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::middleware('guest')->group(function () {
    Route::get('/register',  fn() => view('auth.register'))->name('register');
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/login',     fn() => view('auth.login'))->name('login');
    Route::post('/login',    [UserController::class, 'login'])->middleware('throttle:3,1');
});
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/courses',    [CoursesController::class, 'index'])->name('courses.index');
Route::get('/categories', [CoursesController::class, 'categories'])->name('categories.index');

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])
        ->name('courses.enroll');
            Route::get('/my-courses', [EnrollmentController::class, 'myCourses'])->name('my-courses');
            Route::get('/courses/{course}/learn/{lesson}', [LessonUserController::class, 'learn'])->name('learn');
Route::post('/lessons/{lesson}/complete', [LessonUserController::class, 'complete'])->name('lessons.complete');

});

Route::middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('/instructor/dashboard', [AdminController::class, 'dashboardd'])
        ->name('instructor.dashboard');

    Route::get('/instructor/courses', [CoursesController::class, 'instructorIndex'])
        ->name('instructor.courses.index');
        Route::get('/instructor/students', [CoursesController::class, 'myStudents'])
        ->name('instructor.students.index');

    Route::get('/courses/{course}/sections/{section}/lessons/create', [CoursesController::class, 'createLesson'])->name('lessons.create');
    Route::post('/courses/{course}/sections/{section}/lessons', [CoursesController::class, 'storeLesson'])->name('lessons.store');
    Route::delete('/lessons/{lesson}', [CoursesController::class, 'destroyLesson'])->name('lessons.destroy');

    Route::get('/courses/{course}/preview', [CoursesController::class, 'preview'])->name('courses.preview');

    // Courses
    Route::get('/courses/create',            [CoursesController::class, 'create'])->name('courses.create');
    Route::post('/courses',                  [CoursesController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit',     [CoursesController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}',          [CoursesController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}',       [CoursesController::class, 'destroy'])->name('courses.destroy');
    Route::patch('/courses/{course}/toggle', [CoursesController::class, 'togglePublish'])
        ->name('courses.togglePublish');
    Route::get('/courses/{course}/content',  [CoursesController::class, 'content'])
        ->name('courses.content');

    // Sections
    Route::post('/courses/{course}/sections', [SectionsController::class, 'store'])->name('sections.store');
    Route::put('/sections/{section}',         [SectionsController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}',      [SectionsController::class, 'destroy'])->name('sections.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/dashboard',          'dashboard')->name('dashboard');
        Route::get('/users',              'users')->name('users.index');
        Route::get('/courses',            'courses')->name('courses.index');
        Route::get('/categories',         'categories')->name('categories.index');
        Route::get('/enrollments',        'enrollments')->name('enrollments.index');

        Route::get('/admins/create',      'createAdminForm')->name('admins.create');
        Route::post('/admins',            'createAdmin')->name('admins.store');
        Route::get('/instructors/create', 'createInstructorForm')->name('instructors.create');
        Route::post('/instructors',       'createInstructor')->name('instructors.store');

        Route::get('/users/{user}',                 'showUser')->name('users.show');
        Route::get('/users/{user}/edit',            'editUser')->name('users.edit');
        Route::put('/users/{user}',                 'updateUser')->name('users.update');
        Route::delete('/users/{user}',              'deleteUser')->name('users.destroy');
        Route::delete('/users/{user}/force-delete', 'forceDeleteUser')->name('users.force-delete');
        Route::delete('/courses/{course}',          'destroyCourse')->name('courses.destroy');
        Route::patch('/courses/{course}/toggle',    'toggleCourse')->name('courses.toggle');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile',          [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update',   [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::get('/courses/{course}', [CoursesController::class, 'show'])->name('courses.show');
