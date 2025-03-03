<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubjectRequest;
use App\Http\Requests\Admin\UpdateSubjectRequest;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::withCount('enrollments')->get();
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
    public function store(StoreSubjectRequest $request)
    {
        try {
            $validated = $request->validated();

            Subject::create([
                'code' => $validated['subject_code'],
                'name' => $validated['subject_name'],
                'units' => $validated['units'],
                'semester' => $validated['semester']
            ]);

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject created successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create subject. Please try again.');
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
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        try {
            $validated = $request->validated();

            $subject->update([
                'code' => $validated['subject_code'],
                'name' => $validated['subject_name'],
                'units' => $validated['units'],
                'semester' => $validated['semester']
            ]);

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject updated successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update subject. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        if ($subject->enrollments()->exists()) {
            return redirect()->back()
                ->with('error', "Cannot delete subject {$subject->name} ({$subject->code}). There are students currently enrolled in this subject.");
        }

        $subject->delete();
        return redirect()->back()
                ->with('success', "Subject '{$subject->name}' ({$subject->code}) has been deleted successfully.");
    }
}
