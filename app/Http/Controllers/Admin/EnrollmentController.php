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
            dd($e->getMessage()); // Temporary for debugging
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
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'semester' => 'required|in:1,2,3',
            'academic_year' => 'required|string',
            'status' => 'required|in:enrolled,dropped,completed'
        ]);

        try {
            Enrollment::create($validated);
            return redirect()->route('admin.enrollments.index')
                ->with('success', 'Enrollment created successfully');
        } catch (\Exception $e) {
            return back()->withInput()
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
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'semester' => 'required|in:1,2,3',
            'academic_year' => 'required|string',
            'status' => 'required|in:enrolled,dropped,completed'
        ]);

        try {
            $enrollment->update($validated);
            return redirect()->route('admin.enrollments.index')
                ->with('success', 'Enrollment updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()
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
