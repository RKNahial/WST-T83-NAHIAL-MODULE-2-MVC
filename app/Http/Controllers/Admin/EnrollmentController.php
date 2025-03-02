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
        // Validate the input
        $validated = $request->validate([
            'student_input' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year' => 'required|string',
            'status' => 'required|string'
        ]);

        // Find student by ID or name
        $studentInput = trim($validated['student_input']);
        $student = null;
        
        // Try to find by student_id first
        $student = Student::where('student_id', $studentInput)->first();
        
        // If not found, try to find by name
        if (!$student) {
            $student = Student::where('name', 'like', "%{$studentInput}%")->first();
        }
        
        // If still not found, return with error
        if (!$student) {
            $availableIds = Student::pluck('student_id')->implode(', ');
            return back()
                ->withInput()
                ->withErrors(['student_input' => "Student not found. Available Student IDs: {$availableIds}"]);
        }

        // Get the subject to retrieve its semester
        $subject = Subject::findOrFail($validated['subject_id']);

        try {
            // Create the enrollment
            Enrollment::create([
                'student_id' => $student->id,
                'subject_id' => $validated['subject_id'],
                'academic_year' => $validated['academic_year'],
                'semester' => $subject->semester,
                'status' => $validated['status']
            ]);

            return redirect()->route('admin.enrollments.index')
                ->with('success', 'Student enrolled successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create enrollment: ' . $e->getMessage()]);
        }
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
            return redirect()->route('admin.enrollments.index')
                ->with('success', 'Enrollment deleted successfully');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to delete enrollment. ' . $e->getMessage()]);
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

    public function updateStatus(Request $request, EnrolledSubject $enrolledSubject)
    {
        $request->validate([
            'status' => 'required|in:enrolled,dropped,completed'
        ]);

        // If status is being changed to 'dropped', delete the grade
        if ($request->status === 'dropped') {
            // Delete the associated grade if it exists
            if ($enrolledSubject->grade) {
                $enrolledSubject->grade->delete();
            }
        }

        // Update the status
        $enrolledSubject->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Subject status updated successfully');
    }
}
