<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index', [
            'totalStudents' => Student::count(),
            'totalSubjects' => Subject::count(),
            'currentEnrollments' => Enrollment::count(),
            'recentStudents' => Student::latest()->take(5)->get()
        ]);
    }
}