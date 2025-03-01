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
        $user = auth()->user();
        
        if (!$user || !$user->student) {
            return redirect()->route('login')
                ->with('error', 'Please login as a student to access this page.');
        }

        $currentEnrollment = Enrollment::where('student_id', $user->student->id)
            ->where('semester', 'current')
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