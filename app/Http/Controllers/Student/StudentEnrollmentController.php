<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Subject;

class StudentEnrollmentController extends Controller
{
    public function subjects()
    {
        $student = auth()->user()->student;
        
        // Get all enrollments
        $enrolledSubjects = Enrollment::where('student_id', $student->id)
            ->with(['subject', 'grade'])
            ->get();

        // Get unique academic years for the filter
        $academicYears = Enrollment::where('student_id', $student->id)
            ->distinct()
            ->pluck('academic_year')
            ->sort()
            ->values();

        return view('student.enrollment.subjects', [
            'enrolledSubjects' => $enrolledSubjects,
            'academicYears' => $academicYears
        ]);
    }

    public function history()
    {
        $user = auth()->user();
        
        if (!$user || !$user->student) {
            return redirect()->route('login')
                ->with('error', 'Please login as a student to access this page.');
        }

        $enrollmentHistory = Enrollment::where('student_id', $user->student->id)
            ->where('semester', '!=', 'current')
            ->with('subjects')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.enrollment.history', compact('enrollmentHistory'));
    }
}