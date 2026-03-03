<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // IMPORTANT: regenerate session immediately
        $request->session()->regenerate();

        // Lecturer intent handling
        if (session('intended_role') === 'lecturer') {
            session()->forget('intended_role');
            return redirect()->route('lecturer.login');
        }

        // Admin handling
        if (auth()->user()->is_admin) {
            logger('Login success', ['user' => auth()->user()->id]);
            return redirect()->route('admin.dashboard');
        }

        // Normal users → intended URL or fallback
        return redirect()->intended(route('home'));
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
