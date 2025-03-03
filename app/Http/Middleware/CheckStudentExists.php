<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class CheckStudentExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'student') {
            // Check if the student record still exists
            $student = Student::where('user_id', Auth::id())->first();
            
            if (!$student) {
                // Student record doesn't exist, log them out
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Redirect to landing page with a message
                return redirect('/')->with('error', 'Your student account has been removed from the system.');
            }
        }
        
        return $next($request);
    }
}