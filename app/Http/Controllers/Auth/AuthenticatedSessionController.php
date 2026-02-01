<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

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
    // Authenticate user
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();

    // Restrict coordinator if not approved
    if ($user->role === 'coordinator' && $user->status !== 'approved') {
        Auth::logout();

        $message = $user->status === 'pending'
            ? 'You cannot log in yet. Please wait until you receive approval from admin.'
            : 'Your registration has been rejected. Contact admin for details.';

        throw ValidationException::withMessages([
            'email' => [$message],
        ]);
    }

    // Redirect based on role
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'client') {
        return redirect()->route('client.dashboard');
    }

    if ($user->role === 'coordinator') {
        return redirect()->route('coordinator.dashboard');
    }

    // Fallback safety
    Auth::logout();
    return redirect('/login');
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
