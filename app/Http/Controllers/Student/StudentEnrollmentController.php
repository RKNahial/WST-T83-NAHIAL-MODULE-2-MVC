<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Subject;

class StudentEnrollmentController extends Controller
{
    public function subjects(Request $request)
    {
        try {
            $student = auth()->user()->student;
            
            // Start query builder
            $query = Enrollment::where('student_id', $student->id)
                ->with(['subject', 'grade']);

            // Filter by academic year if selected
            if ($request->filled('academic_year')) {
                $query->where('academic_year', '=', $request->academic_year);
            }

            // Filter by semester if selected
            if ($request->filled('semester')) {
                $semesterMap = [
                    'First Semester' => 1,
                    'Second Semester' => 2,
                    'Summer' => 3
                ];
                $query->where('semester', '=', $semesterMap[$request->semester] ?? $request->semester);
            }

            $enrolledSubjects = $query->get();

            return view('student.enrollment.subjects', [
                'enrolledSubjects' => $enrolledSubjects
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading student subjects: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading subjects.');
        }
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