<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #3E3F29;
            --cream: #F6F8F5;
            --soft-olive: #6B6D4B;
            --accent-green: #A1BC98;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f1f3ef; 
            background-image: radial-gradient(var(--primary) 0.5px, transparent 0.5px);
            background-size: 40px 40px;
        }
        h2 { font-family: 'Playfair Display', serif; }

        .glow {
            position: absolute;
            width: 600px; height: 600px;
            background: var(--accent-green);
            filter: blur(140px); border-radius: 50%;
            opacity: 0.2; z-index: 0;
        }

        /* --- THE ARROW FIX --- */
        select {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: none !important; /* This removes the Tailwind plugin arrow */
        }
        select::-ms-expand {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden text-[#3E3F29]">
    
    <div class="glow -top-20 -left-20"></div>
    <div class="glow bottom-0 right-0" style="background: var(--soft-olive); opacity: 0.15;"></div>

    <div class="w-full max-w-xl relative z-10">
        <div class="bg-white/80 backdrop-blur-xl border border-white shadow-2xl rounded-[3rem] p-10 lg:p-16">
            
            <header class="text-center mb-10">
                <span class="inline-block px-4 py-1 border border-[var(--primary)]/10 rounded-full text-[9px] font-bold tracking-[0.3em] uppercase mb-4 opacity-60">
                    System Access
                </span>
                <h2 class="text-4xl font-bold tracking-tight">Create Account</h2>
                <p class="text-sm text-gray-500 mt-2">Join the elite coordination ecosystem.</p>
            </header>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 ml-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="w-full px-6 py-4 rounded-2xl border-gray-100 bg-white/50 text-sm focus:ring-2 focus:ring-[var(--primary)] focus:border-transparent transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-6 py-4 rounded-2xl border-gray-100 bg-white/50 text-sm focus:ring-2 focus:ring-[var(--primary)] focus:border-transparent transition-all outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 ml-1">Account Role</label>
                    <div class="relative group">
                        <select name="role" required
                                class="w-full px-6 py-4 rounded-2xl border-gray-100 bg-white/50 text-sm focus:ring-2 focus:ring-[var(--primary)] focus:border-transparent transition-all outline-none appearance-none bg-none cursor-pointer pr-12">
                            <option value="" disabled selected>Select your role...</option>
                            <option value="client">Client</option>
                            <option value="coordinator">Coordinator</option>
                            <option value="admin">Admin</option>
                        </select>
                        
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none opacity-40 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 ml-1">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-6 py-4 rounded-2xl border-gray-100 bg-white/50 text-sm focus:ring-2 focus:ring-[var(--primary)] focus:border-transparent transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 ml-1">Confirm</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-6 py-4 rounded-2xl border-gray-100 bg-white/50 text-sm focus:ring-2 focus:ring-[var(--primary)] focus:border-transparent transition-all outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-[var(--primary)] text-white font-bold rounded-2xl shadow-xl shadow-[#3E3F29]/20 hover:scale-[1.01] active:scale-[0.98] transition-all tracking-[0.2em] text-[11px]">
                    CREATE ACCOUNT
                </button>

                <div class="text-center mt-8">
                    <p class="text-xs text-gray-400 font-medium">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-[var(--primary)] font-bold hover:underline underline-offset-4 ml-1">Sign In</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>