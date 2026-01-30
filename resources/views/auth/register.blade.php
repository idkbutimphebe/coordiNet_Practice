<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@300;400;600&display=swap" rel="stylesheet">

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
            width: 600px;
            height: 600px;
            background: var(--accent-green);
            filter: blur(140px);
            border-radius: 50%;
            opacity: 0.2;
            z-index: 0;
        }

        select {
            appearance: none !important;
            background-image: none !important;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden text-[#3E3F29]">

<div class="glow -top-20 -left-20"></div>
<div class="glow bottom-0 right-0" style="background: var(--soft-olive); opacity: 0.15;"></div>

<div class="w-full max-w-xl relative z-10">
    <div class="bg-white/80 backdrop-blur-xl border border-white shadow-2xl rounded-[3rem] p-10 lg:p-16">

        <!-- HEADER -->
        <header class="text-center mb-10">
            <span class="inline-block px-4 py-1 border border-[var(--primary)]/10 rounded-full text-[9px] font-bold tracking-[0.3em] uppercase mb-4 opacity-60">
                System Access
            </span>
            <h2 class="text-4xl font-bold tracking-tight">Create Account</h2>
            <p class="text-sm text-gray-500 mt-2">
                Register as a Client or Coordinator
            </p>
        </header>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- NAME & EMAIL -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Full Name</label>
                    <input type="text" name="name" required
                           class="w-full px-6 py-4 rounded-2xl bg-white/60 text-sm focus:ring-2 focus:ring-[var(--primary)] outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Email</label>
                    <input type="email" name="email" required
                           class="w-full px-6 py-4 rounded-2xl bg-white/60 text-sm focus:ring-2 focus:ring-[var(--primary)] outline-none">
                </div>
            </div>

            <!-- ROLE -->
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">
                    Select Role
                </label>

                <select id="roleSelect" name="role" required
                        class="w-full px-6 py-4 rounded-2xl bg-white/60 text-sm focus:ring-2 focus:ring-[var(--primary)] outline-none">
                    <option value="" disabled selected>Choose role...</option>
                    <option value="client">Client</option>
                    <option value="coordinator">Coordinator</option>
                </select>
            </div>

            <!-- COORDINATOR PLAN (HIDDEN BY DEFAULT) -->
            <div id="planSection" class="hidden">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">
                    Coordinator Plan
                </label>

                <select name="plan"
                        class="w-full px-6 py-4 rounded-2xl bg-white/60 text-sm focus:ring-2 focus:ring-[var(--primary)] outline-none">
                    <option value="" disabled selected>Select plan...</option>
                    <option value="basic">Basic</option>
                    <option value="premium">Premium</option>
                    <option value="elite">Elite</option>
                </select>
            </div>

            <!-- PASSWORD -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-6 py-4 rounded-2xl bg-white/60 text-sm focus:ring-2 focus:ring-[var(--primary)] outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Confirm</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-6 py-4 rounded-2xl bg-white/60 text-sm focus:ring-2 focus:ring-[var(--primary)] outline-none">
                </div>
            </div>

            <!-- SUBMIT -->
            <button type="submit"
                    class="w-full py-5 bg-[var(--primary)] text-white font-bold rounded-2xl tracking-[0.2em] text-[11px] hover:scale-[1.02] transition">
                CREATE ACCOUNT
            </button>

            <!-- LOGIN -->
            <p class="text-center text-xs text-gray-400 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-[var(--primary)] hover:underline">
                    Sign In
                </a>
            </p>

        </form>
    </div>
</div>

<!-- ROLE TO PLAN SCRIPT -->
<script>
    const roleSelect = document.getElementById('roleSelect');
    const planSection = document.getElementById('planSection');

    roleSelect.addEventListener('change', function () {
        if (this.value === 'coordinator') {
            planSection.classList.remove('hidden');
        } else {
            planSection.classList.add('hidden');
        }
    });
</script>

</body>
</html>
