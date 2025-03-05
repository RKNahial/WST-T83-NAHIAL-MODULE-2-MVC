<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\Grade;

class StudentRecordsController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        // Get enrollments with grades, sorted by academic year and semester
        $enrollments = Enrollment::where('student_id', $student->id)
            ->with(['subject', 'grade'])
            ->orderBy('academic_year', 'asc')
            ->orderBy('semester', 'asc')
            ->get();

        // Group enrollments by academic year and semester
        $groupedEnrollments = $enrollments->groupBy(['academic_year', 'semester']);

        return view('student.records.index', [
            'groupedEnrollments' => $groupedEnrollments
        ]);
    }

    public function subjects()
    {
        $student = auth()->user()->student;
        
        $currentEnrollment = Enrollment::where('student_id', $student->id)
            ->where('semester', 'current') // Adjust this based on your semester tracking
            ->first();

        $enrolledSubjects = [];
        $totalUnits = 0;

        if ($currentEnrollment) {
            $enrolledSubjects = $currentEnrollment->subjects;
            $totalUnits = $enrolledSubjects->sum('units');
        }

        return view('student.enrollment.subjects', compact(
            'currentEnrollment',
            'enrolledSubjects',
            'totalUnits'
        ));
    }

    public function history()
    {
        $student = auth()->user()->student;
        
        $enrollmentHistory = Enrollment::where('student_id', $student->id)
            ->where('semester', '!=', 'current') // Exclude current semester
            ->with('subjects')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.enrollment.history', compact('enrollmentHistory'));
    }
}