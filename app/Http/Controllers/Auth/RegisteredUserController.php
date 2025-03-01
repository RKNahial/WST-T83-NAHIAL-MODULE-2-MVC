<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Check email domain to determine role
        $email = $request->email;
        if (!str_ends_with($email, '@buksu.edu.ph') && !str_ends_with($email, '@student.buksu.edu.ph')) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Please use a valid institutional email address (@buksu.edu.ph for admin or @student.buksu.edu.ph for students)']);
        }

        // Set role based on email domain
        $role = str_ends_with($email, '@buksu.edu.ph') ? 'admin' : 'student';

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'student_id' => $role === 'student' ? $this->extractStudentId($email) : null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect($role === 'admin' ? '/admin/dashboard' : '/dashboard');
    }

    /**
     * Extract student ID from email address
     */
    private function extractStudentId(string $email): string
    {
        // Assuming the email format is studentid@student.buksu.edu.ph
        return explode('@', $email)[0];
    }
}
