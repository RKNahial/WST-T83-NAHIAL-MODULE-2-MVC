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
        $validated = $request->validate([
            'student_input' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year' => 'required|string',
            'status' => 'required|in:enrolled,dropped,completed'
        ]);

        try {
            // Check if student exists
            $student = Student::where('student_id', $validated['student_input'])
                ->orWhere('name', 'LIKE', "%{$validated['student_input']}%")
                ->first();

            if (!$student) {
                return back()
                    ->withInput()
                    ->withErrors(['student_input' => 'Student not found. Please add the student first before enrolling.']);
            }

            // Get the subject and its semester
            $subject = Subject::find($validated['subject_id']);

            // Create the enrollment with subject's semester
            Enrollment::create([
                'student_id' => $student->id,
                'subject_id' => $validated['subject_id'],
                'semester' => $subject->semester,  // Get semester directly from subject
                'academic_year' => $validated['academic_year'],
                'status' => $validated['status']
            ]);

            return redirect()->route('admin.enrollments.index')
                ->with('success', 'Enrollment created successfully.');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry') && str_contains($e->getMessage(), 'unique')) {
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'This student is already enrolled in this subject for the selected semester and academic year.']);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create enrollment. ' . $e->getMessage()]);
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
}
