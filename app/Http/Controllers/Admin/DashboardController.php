<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current academic year and semester from settings
        $currentAcademicYear = Setting::where('name', 'current_academic_year')->first()->value ?? '2023-2024';
        $currentSemester = Setting::where('name', 'current_semester')->first()->value ?? 1;

        // Get total students (all enrolled students)
        $totalStudents = Student::count();

        // Get total subjects
        $totalSubjects = Subject::count();

        // Get current enrollments (only for current semester)
        $currentEnrollments = Enrollment::where('academic_year', $currentAcademicYear)
            ->where('semester', $currentSemester)
            ->where('status', 'enrolled')  // Only count active enrollments
            ->count();

        // Get recent students with their current semester enrollments
        $recentStudents = Student::with(['enrollments' => function($query) use ($currentAcademicYear, $currentSemester) {
            $query->where('academic_year', $currentAcademicYear)
                  ->where('semester', $currentSemester)
                  ->where('status', 'enrolled');
        }])
        ->latest()
        ->take(5)
        ->get();

        return view('admin.dashboard.index', [
            'totalStudents' => $totalStudents,
            'totalSubjects' => $totalSubjects,
            'currentEnrollments' => $currentEnrollments,
            'recentStudents' => $recentStudents,
            'currentSemester' => $this->getSemesterText($currentSemester),
            'currentAcademicYear' => $currentAcademicYear
        ]);
    }

    private function getSemesterText($semester)
    {
        switch ($semester) {
            case 1:
                return 'First Semester';
            case 2:
                return 'Second Semester';
            case 3:
                return 'Summer';
            default:
                return '';
        }
    }
}