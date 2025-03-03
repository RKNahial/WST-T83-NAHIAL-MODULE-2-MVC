<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Student\StudentAcademicController;
use App\Http\Controllers\Student\StudentEnrollmentController;
use App\Http\Controllers\Student\StudentRecordsController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ADMIN ROUTE
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Students
    Route::post('students/{student}/archive', [StudentController::class, 'archive'])->name('students.archive');
    Route::resource('students', StudentController::class);

    // Subjects
    Route::resource('subjects', SubjectController::class);

    // Enrollments
    Route::resource('enrollments', EnrollmentController::class);

    // Grades
    Route::resource('grades', GradeController::class);

    // Admins
    Route::resource('admins', AdminController::class);
});

// STUDENT ROUTE
Route::middleware(['auth', \App\Http\Middleware\StudentMiddleware::class])->prefix('student')->name('student.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Academic Records
    Route::get('/records', [StudentRecordsController::class, 'index'])->name('records.index');
    
    // Enrollment
    Route::prefix('enrollment')->name('enrollment.')->group(function () {
        Route::get('/subjects', [StudentEnrollmentController::class, 'subjects'])->name('subjects');
        Route::get('/history', [StudentEnrollmentController::class, 'history'])->name('history');
    });
});

// Regular registration (for admin)
Route::get('register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

require __DIR__.'/auth.php';
