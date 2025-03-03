<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $status = $request->query('status', 'active');
            $students = Student::where('is_archived', $status === 'archived')->get();
            
            return view('admin.students.index', [
                'students' => $students,
                'currentStatus' => $status
            ]);
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
    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();
        
        // Generate a random password
        $tempPassword = Str::random(8);
        
        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($tempPassword),
            'role' => 'student',
        ]);
        
        // Create student record
        Student::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'student_id' => $validated['student_id'],
            'email' => $validated['email'],
            'course' => $validated['course'],
            'year_level' => $validated['year_level'],
        ]);
        
        return redirect()->route('admin.students.create')
            ->with('success', 'Student added successfully!')
            ->with('temp_password', $tempPassword);
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
    public function update(UpdateStudentRequest $request, Student $student)
    {
        try {
            // Update user information
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'student_id' => $request->student_id,
            ]);

            // Update student information
            $student->update([
                'name' => $request->name,
                'email' => $request->email,
                'student_id' => $request->student_id,
                'course' => $request->course,
                'year_level' => $request->year_level
            ]);

            return redirect()->route('admin.students.index')
                ->with('success', 'Student updated successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update student. Please try again.');
        }
    }

    /**
     * Archive the specified resource.
     */
    public function destroy(Student $student)
    {
        try {
            $student->update(['is_archived' => true]);
            return redirect()->route('admin.students.index')
                ->with('success', 'Student archived successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to archive student. Please try again.');
        }
    }

    public function search(Request $request)
    {
        try {
            $search = $request->get('search');
            
            $students = Student::where('is_archived', false)
                ->where(function($query) use ($search) {
                    $query->where('student_id', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%");
                })
                ->limit(10)
                ->get(['id', 'student_id', 'name']);

            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function archive(Student $student)
    {
        try {
            $student->update([
                'is_archived' => !$student->is_archived
            ]);

            $message = $student->is_archived ? 'archived' : 'unarchived';
            return redirect()->back()->with('success', "Student successfully {$message}");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update student status. Please try again.');
        }
    }
}
