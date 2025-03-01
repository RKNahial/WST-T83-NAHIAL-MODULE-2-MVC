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
        $student = auth()->user()->student;
        
        // Load enrollments with their relationships
        $enrollmentHistory = Enrollment::where('student_id', $student->id)
            ->with(['subject', 'grade']) // Use the correct relationship names
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return view('student.enrollment.history', [
            'enrollmentHistory' => $enrollmentHistory
        ]);
    }
}