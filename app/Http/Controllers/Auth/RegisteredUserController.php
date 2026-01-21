<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate including the new 'role' field
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string', 'in:client,coordinator,admin'], 
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Create user with 'role'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role, 
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // 3. Dynamic Redirect based on Role (Matches your web.php names)
        if ($user->role === 'admin') {
            return redirect()->intended(route('dashboard'));
        } 
        
        if ($user->role === 'coordinator') {
            return redirect()->intended(route('coordinator.dashboard'));
        }

        if ($user->role === 'client') {
            return redirect()->intended(route('client.dashboard'));
        }

        // Default fallback
        return redirect()->intended(route('dashboard'));
    }
}
