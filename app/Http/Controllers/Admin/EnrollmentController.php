<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $enrollments = Enrollment::with(['student', 'subject'])->get();
            return view('admin.enrollments.index', compact('enrollments'));
        } catch (\Exception $e) {
            \Log::error('Enrollment index error: ' . $e->getMessage());
            dd($e->getMessage()); 
            return back()->with('error', 'An error occurred while loading enrollments.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::all();
        $subjects = Subject::all();
        $academicYears = $this->generateAcademicYears();
        
        return view('admin.enrollments.create', compact('students', 'subjects', 'academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Find student
            $student = $this->findStudent($request->student_input);
            
            // Get subject
            $subject = Subject::findOrFail($request->subject_id);

            // Check for existing enrollment
            $existingEnrollment = Enrollment::where('student_id', $student->id)
                ->where('subject_id', $request->subject_id)
                ->where('academic_year', $request->academic_year)
                ->where('semester', $subject->semester)
                ->first();

            if ($existingEnrollment) {
                return back()
                    ->withInput()
                    ->with('error', 'Student is already enrolled in this subject for the selected semester.');
            }
            
            // Create enrollment
            Enrollment::create([
                'student_id' => $student->id,
                'subject_id' => $request->subject_id,
                'academic_year' => $request->academic_year,
                'semester' => $subject->semester,
                'status' => $request->status
            ]);

            // Single success message
            return redirect()->route('admin.enrollments.index')
                ->with('success', 'Student enrolled successfully.');
            
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to enroll student. Please try again.');
        }
    }

    private function findStudent($input)
    {
        // Try to find by student_id first
        $student = Student::where('student_id', $input)->first();
        
        // If not found, try to find by name
        if (!$student) {
            $student = Student::where('name', 'like', "%{$input}%")->first();
        }
        
        // If still not found, throw an exception
        if (!$student) {
            throw new \Exception('Student not found');
        }

        return $student;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollment)
    {
        $students = Student::all();
        $subjects = Subject::all();
        $academicYears = $this->generateAcademicYears();
        
        return view('admin.enrollments.edit', compact('enrollment', 'students', 'subjects', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'semester' => 'required|in:1,2,3',
            'academic_year' => 'required|string',
            'status' => 'required|in:enrolled,dropped,completed'
        ]);

        try {
            $enrollment->update($validated);

            return redirect()->route('admin.enrollments.index')
                ->with('success', 'Student enrolled successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update enrollment. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        try {
            $enrollment->delete();
            
            // Single success message
            return redirect()->route('admin.enrollments.index')
                ->with('success', 'Enrollment deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete enrollment. Please try again.');
        }
    }

    private function generateAcademicYears()
    {
        $currentYear = date('Y');
        $years = [];
        
        for ($i = -1; $i <= 1; $i++) {
            $year = $currentYear + $i;
            $academicYear = $year . '-' . ($year + 1);
            $years[$academicYear] = $academicYear;
        }
        
        return $years;
    }

    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        try {
            $enrollment->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'Status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update status.');
        }
    }
}
