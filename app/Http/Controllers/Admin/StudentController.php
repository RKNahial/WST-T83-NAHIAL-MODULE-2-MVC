<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $students = Student::all();
            return view('admin.students.index', compact('students'));
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|unique:students,student_id',
                'name' => 'required',
                'email' => 'required|email|unique:students,email',
                'course' => 'required',
                'year_level' => 'required|in:1,2,3,4'
            ]);

            Student::create($validated);

            return redirect()->route('admin.students.index')
                ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create student.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|unique:students,student_id,' . $student->id,
                'name' => 'required',
                'email' => 'required|email|unique:students,email,' . $student->id,
                'course' => 'required',
                'year_level' => 'required|in:1,2,3,4'
            ]);

            $student->update($validated);

            return redirect()->route('admin.students.index')
                ->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update student.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            $student->delete();

            return redirect()->route('admin.students.index')
                ->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.students.index')
                ->with('error', 'Failed to delete student.');
        }
    }

    public function search(Request $request)
    {
        try {
            $search = $request->get('search');
            
            $students = Student::where('student_id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->limit(10)
                ->get(['id', 'student_id', 'name']);

            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
