<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::all();
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'subject_code' => 'required|string|unique:subjects,code',
                'subject_name' => 'required|string|max:255',
                'units' => 'required|integer|min:1|max:6',
                'semester' => 'required|in:1,2,3'
            ]);

            $subject = new Subject();
            $subject->code = $validated['subject_code'];
            $subject->name = $validated['subject_name'];
            $subject->units = $validated['units'];
            $subject->semester = $validated['semester'];
            $subject->save();

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject created successfully');
        } catch (\Exception $e) {
            \Log::error('Subject creation failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create subject. ' . $e->getMessage()]);
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
        $subject = Subject::findOrFail($id);
        return view('admin.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $subject = Subject::findOrFail($id);
            
            $validated = $request->validate([
                'subject_code' => 'required|string|unique:subjects,code,' . $id,
                'subject_name' => 'required|string|max:255',
                'units' => 'required|integer|min:1|max:6',
                'semester' => 'required|in:1,2,3'
            ]);

            $subject->code = $validated['subject_code'];
            $subject->name = $validated['subject_name'];
            $subject->units = $validated['units'];
            $subject->semester = $validated['semester'];
            $subject->save();

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject updated successfully');
        } catch (\Exception $e) {
            \Log::error('Subject update failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update subject. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->delete();

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Subject deletion failed: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Failed to delete subject. ' . $e->getMessage()]);
        }
    }
}
