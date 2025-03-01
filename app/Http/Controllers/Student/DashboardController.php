<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated student
        $student = auth()->user()->student;
        
        // Get current enrollments with subjects and grades
        $currentSubjects = Enrollment::where('student_id', $student->id)
            ->where('status', 'enrolled')
            ->with(['subject', 'grade'])
            ->get()
            ->map(function ($enrollment) {
                return [
                    'code' => $enrollment->subject->code,
                    'name' => $enrollment->subject->name,
                    'grade' => $enrollment->grade ? $enrollment->grade->grade : 'Ongoing'
                ];
            });

        // Calculate total enrolled subjects
        $totalEnrolledSubjects = $currentSubjects->count();

        // Get enrollments with grades
        $enrollments = Enrollment::where('student_id', $student->id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->with('grade')
            ->get();

        // Calculate GPA
        $gradesSum = 0;
        $gradesCount = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->grade) {
                $gradesSum += $enrollment->grade->grade;
                $gradesCount++;
            }
        }

        // Calculate GPA
        $gpa = $gradesCount > 0 ? number_format($gradesSum / $gradesCount, 2) : '0.00';

        return view('student.dashboard.index', [
            'currentSubjects' => $currentSubjects,
            'totalEnrolledSubjects' => $totalEnrolledSubjects,
            'gpa' => $gpa,
            'currentSemester' => 'Current Semester'
        ]);
    }
}
