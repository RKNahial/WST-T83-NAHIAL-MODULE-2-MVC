<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGradeRequest;
use App\Http\Requests\Admin\UpdateGradeRequest;
use App\Models\Grade;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Enrollment::with(['student', 'subject', 'grade'])
                ->whereHas('student', function($query) {
                    $query->where('is_archived', false);
                })
                ->whereIn('status', ['enrolled', 'completed']);

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

            $enrollments = $query->get();
            
            return view('admin.grades.index', compact('enrollments'));
        } catch (\Exception $e) {
            Log::error('Error loading grades: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading grades.');
        }
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
    public function store(StoreGradeRequest $request)
    {
        try {
            // Check if a grade already exists for this enrollment
            $existingGrade = Grade::where('enrollment_id', $request->enrollment_id)->first();
            
            if ($existingGrade) {
                // If grade exists, update it instead of creating new
                $existingGrade->update([
                    'grade' => $request->validated()['grade']  // Use validated data
                ]);
                
                return redirect()->route('admin.grades.index')
                    ->with('success', 'Grade updated successfully');
            }

            // If no existing grade, create new one using validated data
            $grade = Grade::create($request->validated());
            
            return redirect()->route('admin.grades.index')
                ->with('success', 'Grade added successfully');
            
        } catch (\Exception $e) {
            Log::error('Error storing grade: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to save grade. ' . $e->getMessage()]);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        try {
            $grade->update($request->validated());

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
