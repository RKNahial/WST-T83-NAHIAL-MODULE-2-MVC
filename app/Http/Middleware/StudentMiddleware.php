<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'student') {
            // Check if the user has a student profile
            if (!auth()->user()->student) {
                auth()->logout();
                return redirect()->route('login')
                    ->with('error', 'Student profile not found. Please contact administrator.');
            }
            return $next($request);
        }
        
        return redirect()->route('login');
    }
}
