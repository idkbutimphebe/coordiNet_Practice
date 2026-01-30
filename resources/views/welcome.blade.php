<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evently | Unforgettable Moments</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #3E3F29;
            --soft-olive: #6B6D4B;
            --cream: #F6F8F5;
            --accent: #E9EED9;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            /* FIXED: Changed --bg to --cream to match your root variables */
            background: linear-gradient(135deg, var(--cream) 0%, var(--accent) 50%, var(--cream) 100%);
            color: var(--primary); 
            overflow-x: hidden;
            margin: 0;
        }

        h1, h2 { font-family: 'Playfair Display', serif; }

        .glass-nav {
            background: rgba(246, 248, 245, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(62, 63, 41, 0.08);
        }

        .btn-luxury {
            background: var(--primary);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(62, 63, 41, 0.15);
        }

        .btn-luxury:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        /* The "No-Photo" Visual: A static abstract shape */
        .abstract-blob {
            background: linear-gradient(45deg, var(--primary), var(--soft-olive));
            border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; /* Fixed static shape */
            position: relative;
            overflow: hidden;
        }

        /* Subtle inner shimmer pattern */
        .abstract-blob::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.1) 1px, transparent 0);
            background-size: 24px 24px;
        }
    </style>
</head>

<body class="min-h-screen">

<div class="max-w-3xl mx-auto mt-6">
    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            {{ session('info') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
</div>

    <nav class="glass-nav sticky top-0 z-50 px-8 py-5">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="text-3xl font-bold tracking-tighter italic">
                Coordinator<span class="text-[10px] ml-1 not-italic opacity-40 font-normal">SYSTEM</span>
            </div>
            
            <div class="flex items-center gap-10 text-[11px] font-bold uppercase tracking-[0.2em]">
@if (Route::has('login'))
    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
        @auth
            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Sign in</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
            @endif
        @endauth
    </div>
@endif

            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-8 py-20 lg:py-32 grid lg:grid-cols-2 gap-20 items-center">
        
        <div class="space-y-10">
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-white/40 backdrop-blur-md border border-white/60 rounded-full">
                <div class="flex gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#3E3F29]"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#3E3F29] opacity-40"></span>
                </div>
                <span class="text-[10px] font-bold tracking-[0.2em] uppercase opacity-60">Elite Management</span>
            </div>
            
            <h1 class="text-8xl lg:text-[9rem] font-bold leading-[0.85] tracking-tight">
                Plan <br> <span class="italic font-light text-[#6B6D4B]">Artfully.</span>
            </h1>
            
            <p class="text-xl text-gray-500 max-w-sm leading-relaxed font-light">
                A minimal, powerful workspace designed for the world's finest event coordinators and their clients.
            </p>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-10 pt-6">
                <a href="{{ route('register') }}" class="btn-luxury px-12 py-6 rounded-full font-bold text-xl">
                    Get Started
                </a>
                <div class="group cursor-default">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-40">Trusted by</p>
                    <p class="text-sm font-semibold tracking-tight">Premium Coordinators</p>
                </div>
            </div>
        </div>

        <div class="relative flex justify-center items-center">
            <div class="absolute w-[120%] h-[120%] bg-white/30 blur-[100px] rounded-full"></div>
            
            <div class="relative z-10 w-full max-w-[500px] aspect-square">
                <div class="abstract-blob w-full h-full shadow-[0_50px_100px_-20px_rgba(62,63,41,0.4)] border-[1px] border-white/20"></div>
                
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white/10 backdrop-blur-md border border-white/20 p-10 rounded-[3rem] w-[80%] shadow-2xl">
                   <div class="space-y-6">
                        <div class="h-px w-12 bg-white/50"></div>
                        <h2 class="text-4xl text-white leading-tight">Crafting <br> Perfect <br> Moments.</h2>
                        <p class="text-xs text-white/60 leading-relaxed tracking-wide uppercase">Unified coordination ecosystem.</p>
                   </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="py-20 flex flex-col items-center gap-6">
        <div class="h-[1px] w-12 bg-[#3E3F29] opacity-10"></div>
        <p class="text-[9px] font-bold uppercase tracking-[0.6em] opacity-30">
            © {{ date('Y') }} Evently Coordination System
        </p>
    </footer>

</body>
</html>