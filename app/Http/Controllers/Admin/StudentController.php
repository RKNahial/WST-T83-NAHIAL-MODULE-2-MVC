<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'student_id' => 'required|unique:users',
            'course' => 'required',
            'year_level' => 'required'
        ]);

        // Generate a temporary password
        $temporaryPassword = substr(str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&'), 0, 10);

        try {
            // Create user account for student
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'student_id' => $request->student_id,
                'password' => Hash::make($temporaryPassword),
                'role' => 'student'
            ]);

            // Create student record with all required fields
            Student::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'student_id' => $request->student_id,
                'course' => $request->course,
                'year_level' => $request->year_level
            ]);

            return back()
                ->with('success', 'Student added successfully!')
                ->with('temp_password', $temporaryPassword);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create student. ' . $e->getMessage()]);
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
