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
    // 1. Validate input
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        'role' => ['required', 'string', 'in:client,coordinator,admin'], 
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // 2. Create user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => Hash::make($request->password),
    ]);

        event(new Registered($user));

   // 3. Redirect based on role 

     if ($user->role === 'client') {
        // Client can log in immediately
        return redirect()->route('login')
            ->with('success', 'Registration successful! You can now log in.');
    }       
   
     if ($user->role === 'coordinator') {
        // Coordinator cannot log in yet
        return redirect('/')
            ->with('info', 'Registration submitted. Please wait for a notification from the admin.');


    // Admin fallback (if needed)
    return redirect()->route('login')
        ->with('success', 'Admin registration successful! You can now log in.');       
}

}
}
