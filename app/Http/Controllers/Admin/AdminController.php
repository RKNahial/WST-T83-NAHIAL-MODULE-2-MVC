<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\EnrolledSubject;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $admins = User::where('role', 'admin')->get();
            return view('admin.admins.index', compact('admins'));
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@buksu\.edu\.ph$/'
            ],
        ], [
            'email.regex' => 'Email must use the @buksu.edu.ph domain.'
        ]);

        // Generate a temporary password
        $temporaryPassword = substr(str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&'), 0, 10);

        try {
            // Create user account for admin
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($temporaryPassword),
                'role' => 'admin'
            ]);

            return back()
                ->with('success', 'Admin added successfully!')
                ->with('temp_password', $temporaryPassword);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create admin. ' . $e->getMessage()]);
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
    public function edit(User $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $admin->id,
                'regex:/^[a-zA-Z0-9._%+-]+@buksu\.edu\.ph$/'
            ],
        ], [
            'email.regex' => 'Email must use the @buksu.edu.ph domain.'
        ]);

        try {
            $admin->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return redirect()->route('admin.admins.index')
                ->with('success', 'Admin updated successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update admin. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        try {
            // Begin a database transaction
            DB::beginTransaction();

            // Delete the user record
            $admin->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->route('admin.admins.index')
                ->with('success', 'Admin deleted successfully.');

        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();

            return redirect()->route('admin.admins.index')
                ->with('error', 'Failed to delete admin: ' . $e->getMessage());
        }
    }
}
