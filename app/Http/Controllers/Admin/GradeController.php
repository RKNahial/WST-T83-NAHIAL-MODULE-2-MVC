<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enrollments = Enrollment::with(['student', 'subject', 'grade'])
            ->where('status', 'enrolled')
            ->get();
        
        return view('admin.grades.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'grade' => 'required|numeric|between:1.00,5.00',
        ]);
        
        // Create the grade with the validated data
        $grade = Grade::create($validated);
        
        return redirect()->route('admin.grades.index')->with('success', 'Grade added successfully');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        try {
            $validated = $request->validate([
                'grade' => 'required|numeric|in:1.00,1.25,1.50,1.75,2.00,2.25,2.50,2.75,3.00,5.00'
            ]);

            $grade->update($validated);

            return redirect()->route('admin.grades.index')
                ->with('success', 'Grade updated successfully.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update grade. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        try {
            $grade->delete();
            
            return redirect()->route('admin.grades.index')
                ->with('success', 'Grade deleted successfully.');
            
        } catch (\Exception $e) {
            Log::error('Error deleting grade: ' . $e->getMessage());
            
            return redirect()->route('admin.grades.index')
                ->with('error', 'Failed to delete grade.');
        }
    }
}
