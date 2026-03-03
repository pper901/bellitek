<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsLecturer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Must be authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Must be a lecturer
        if (!auth()->user()->is_lecturer) {
            abort(403, 'Lecturer access only');
        }

        return $next($request);
    }
}
