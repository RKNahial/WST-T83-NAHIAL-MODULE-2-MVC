<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Grade;

class GwaSummaryController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        // Get all enrollments with grades
        $enrollments = Enrollment::where('student_id', $student->id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->with(['subject', 'grade'])
            ->get();

        // Calculate GWA
        $totalGrades = 0;
        $gradeCount = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->grade) {
                $totalGrades += $enrollment->grade->grade;
                $gradeCount++;
            }
        }

        $gwa = $gradeCount > 0 ? number_format($totalGrades / $gradeCount, 2) : '0.00';

        return view('student.records.index', [
            'enrollments' => $enrollments,
            'gwa' => $gwa
        ]);
    }
}
