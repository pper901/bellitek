<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LecturerEntryController extends Controller
{
    public function __invoke(Request $request)
    {
        // Not logged in → login first
        if (!auth()->check()) {
            session(['intended_role' => 'lecturer']);
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Already a lecturer → dashboard
        if ($user->is_lecturer) {
            return redirect()->route('lecturer.dashboard');
        }

        // Logged in but not lecturer → confirmation page
        return view('pages.classroom.request-lecturer');
    }
}
