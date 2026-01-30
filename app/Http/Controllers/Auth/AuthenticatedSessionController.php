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
        $request->session()->regenerate();

        $user = Auth::user();

        // ================= ROLE-BASED REDIRECT =================

        // ADMIN → /dashboard
        if ($user->role === 'admin') {
<<<<<<< Updated upstream
            return redirect()->route('dashboard');
        }

        // COORDINATOR → /coordinator/dashboard
        if ($user->role === 'coordinator') {
            return redirect()->route('coordinator.dashboard');
        }

        // CLIENT → /client/dashboard
        if ($user->role === 'client') {
            return redirect()->route('client.dashboard');
        }

        // Fallback (safety)
        Auth::logout();
        return redirect('/login');
=======
            return redirect()->intended(route('dashboard'));
        } 
        
        if ($user->role === 'coordinator') {
            return redirect()->intended(route('coordinator.dashboard'));
        }

        // Default for clients
        return redirect()->intended(route('client.dashboard'));
        // --- CUSTOM ROLE-BASED REDIRECT END ---
>>>>>>> Stashed changes
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
