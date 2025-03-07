<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = auth()->user();
        
        // Check if user has an associated student record
        if (!$user || !$user->student) {
            // Redirect to login with an error message
            return redirect()->route('login')
                ->with('error', 'Student profile not found. Please contact administrator.');
        }

        // Rest of your dashboard logic
        $student = $user->student;
        
        // Get current academic year and semester from settings
        $currentAcademicYear = Setting::where('name', 'current_academic_year')->first()->value ?? '2023-2024';
        $currentSemester = Setting::where('name', 'current_semester')->first()->value ?? 1;

        // Get current semester subjects (excluding dropped)
        $currentSubjects = Enrollment::where('student_id', $student->id)
            ->where('academic_year', $currentAcademicYear)
            ->where('semester', $currentSemester)
            ->whereIn('status', ['enrolled', 'completed'])
            ->with(['subject', 'grade'])
            ->get();

        // Calculate current semester GWA (excluding dropped)
        $totalGrades = 0;
        $gradeCount = 0;

        foreach ($currentSubjects as $enrollment) {
            // Only include grades from enrolled and completed subjects
            if ($enrollment->status != 'dropped' && 
                $enrollment->grade && 
                is_numeric($enrollment->grade->grade)) {
                $grade = $enrollment->grade->grade;
                
                // Only include valid grades (not failed or incomplete)
                if ($grade > 0 && $grade <= 5.0) {
                    $totalGrades += $grade;
                    $gradeCount++;
                }
            }
        }

        // Calculate simple average of grades
        $currentGWA = $gradeCount > 0 
            ? number_format($totalGrades / $gradeCount, 2) 
            : '0.00';

        return view('student.dashboard.index', [
            'totalEnrolledSubjects' => $currentSubjects->count(),
            'GWA' => $currentGWA,
            'currentSubjects' => $currentSubjects,
            'currentSemester' => $this->getSemesterText($currentSemester) . ' ' . $currentAcademicYear
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
