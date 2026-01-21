<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Evently</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #3E3F29;
            --cream: #F6F8F5;
            --soft-olive: #6B6D4B;
            --accent-green: #A1BC98;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--cream); }
        h1 { font-family: 'Playfair Display', serif; }

        .pattern-bg {
            background-color: var(--primary);
            background-image: radial-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            position: relative;
        }

        .glow {
            position: absolute;
            width: 500px;
            height: 500px;
            background: var(--soft-olive);
            filter: blur(150px);
            border-radius: 50%;
            opacity: 0.2;
            pointer-events: none;
        }
    </style>
</head>

<body class="h-screen w-screen overflow-hidden">
<div class="flex h-full w-full">

    <div class="hidden lg:flex w-3/5 pattern-bg text-white flex-col justify-between p-24 overflow-hidden">
        
        <div class="glow -top-20 -left-20"></div>
        <div class="glow bottom-0 right-0" style="background: var(--accent-green); opacity: 0.1;"></div>

        <div class="relative z-10">
            <div class="flex flex-col items-start">
                <span class="inline-block px-4 py-1 border border-white/20 rounded-full text-[10px] font-bold tracking-[0.3em] uppercase bg-white/5 text-white/70">
                    Capstone Project 2026
                </span>
                <div class="mt-3 ml-1 flex items-center gap-2">
                    <div class="h-[1px] w-4 bg-[var(--accent-green)] opacity-50"></div>
                    <span class="text-[10px] font-semibold tracking-widest uppercase opacity-40 text-white">
                        Phebe & Tonet
                    </span>
                </div>
            </div>
        </div>
        
        <div class="relative z-10">
            <h1 class="text-8xl lg:text-9xl font-bold leading-[0.85] tracking-tight mb-8">
                Dream. <br> 
                Plan. <br>
                <span class="italic font-light text-[var(--accent-green)]">Deliver.</span>
            </h1>
            <p class="text-lg text-white/50 font-light max-w-md leading-relaxed">
                A professional workspace designed to streamline coordination and unify event management.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-4">
            <div class="h-[1px] w-12 bg-white/20"></div>
            <p class="text-[9px] font-bold uppercase tracking-[0.5em] text-white/30">
                Event Coordination System
            </p>
        </div>
    </div>

    <div class="w-full lg:w-2/5 flex items-center justify-center px-16 bg-[var(--cream)]">
        <div class="w-full max-w-sm">
            
            <header class="mb-12">
                <h2 class="text-4xl font-bold text-[var(--primary)] tracking-tight mb-3">Welcome Back</h2>
                <p class="text-sm text-gray-400">Enter your credentials to access your portal.</p>
            </header>

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 text-xs text-green-700 rounded-2xl border border-green-100 italic">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-7">
                @csrf

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Email Address</label>
                    <input type="email" name="email" required autofocus
                           class="w-full px-6 py-4 rounded-2xl border-gray-200 bg-white text-sm focus:ring-2 focus:ring-[var(--primary)] focus:border-transparent transition-all shadow-sm outline-none">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2.5 ml-1">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400">Password</label>
                        <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-[var(--primary)] uppercase tracking-widest hover:opacity-50">Forgot?</a>
                    </div>
                    <input type="password" name="password" required
                           class="w-full px-6 py-4 rounded-2xl border-gray-200 bg-white text-sm focus:ring-2 focus:ring-[var(--primary)] focus:border-transparent transition-all shadow-sm outline-none">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)]">
                    <label for="remember" class="ml-2.5 text-xs text-gray-400 font-medium cursor-pointer">Remember me</label>
                </div>

                <button type="submit" class="w-full py-5 bg-[var(--primary)] text-white font-bold rounded-2xl shadow-xl shadow-[#3E3F29]/20 hover:opacity-95 active:scale-[0.98] transition-all tracking-widest text-xs">
                    LOG IN
                </button>

                <div class="flex flex-col gap-6 text-center mt-10">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                        <div class="relative flex justify-center text-[9px] uppercase tracking-[0.3em]"><span class="bg-[var(--cream)] px-4 text-gray-300 font-bold">New Account?</span></div>
                    </div>

                    <a href="{{ route('register') }}" class="text-sm font-bold text-[var(--primary)] hover:underline underline-offset-8 decoration-2">
                        Create an account
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
</body>
</html>