<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coordinator</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#A1BC98] text-gray-900">

<!-- APP WRAPPER -->
<div class="flex min-h-screen w-full">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#778873] text-white flex-shrink-0">

<!-- ADMIN LABEL -->
<div class="px-4 py-5 border-b border-white/20 flex justify-center">
    <h2 class="text-lg font-semibold tracking-wide">
        Coordinator
    </h2>
</div>

        <nav class="p-4 space-y-2">
<!-- Dashboard -->
<a href="{{ route('coordinator.dashboard') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
   {{ request()->routeIs('coordinator.dashboard') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M3 12l2-2 7-7 7 7M5 10v10h14V10"/>
    </svg>
    <span>Dashboard</span>
</a>

<!-- Bookings -->
<a href="{{ route('coordinator.bookings') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
   {{ request()->routeIs('coordinator.bookings') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"/>
        <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"/>
        <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"/>
        <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"/>
    </svg>
    <span>Bookings</span>
</a>

<!-- Schedule -->
<a href="{{ route('coordinator.schedule') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
   {{ request()->routeIs('coordinator.schedule') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <span>Schedule</span>
</a>

<!-- Ratings -->
<a href="{{ route('coordinator.ratings') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
   {{ request()->routeIs('coordinator.ratings') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l2.036 6.26
              6.573.012c.969.002 1.371 1.24.588 1.81l-5.324 3.873
              2.01 6.27c.285.89-.755 1.63-1.54 1.09L12 18.347
              l-5.294 3.695c-.785.54-1.825-.2-1.54-1.09l2.01-6.27
              -5.324-3.873c-.783-.57-.38-1.808.588-1.81l6.573-.012
              2.036-6.26z"/>
    </svg>
    <span>Ratings & Feedback</span>
</a>

<!-- Income -->
<a href="{{ route('coordinator.income') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
   {{ request()->routeIs('coordinator.income') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7H14a3.5 3.5 0 010 7H6"/>
    </svg>
    <span>Income / Payments</span>
</a>

<!-- Subscription -->
<a href="{{ route('coordinator.subscription') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
   {{ request()->routeIs('coordinator.subscription') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <rect x="2" y="5" width="20" height="14" rx="2" ry="2" stroke-width="2"/>
        <line x1="2" y1="10" x2="22" y2="10" stroke-width="2"/>
        <line x1="6" y1="15" x2="10" y2="15" stroke-width="2"/>
    </svg>
    <span>Subscription</span>
</a>

<!-- Profile -->
<a href="{{ route('coordinator.profile') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
   {{ request()->routeIs('coordinator.profile') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
        <circle cx="12" cy="7" r="4" stroke-width="2"/>
    </svg>
    <span>My Profile</span>
</a>

       
            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="flex items-center gap-3 w-full px-4 py-2 rounded
                           hover:bg-red-500/30 text-left transition">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M17 16l4-4m0 0l-4-4m4 4H7
                                 M7 4h6v4"/>
                    </svg>

                    <span>Logout</span>
                </button>
            </form>

        </nav>
    </aside>
<!-- MAIN CONTENT -->
    <main class="flex-1 min-w-0 bg-[#A1BC98] p-8 overflow-x-auto">
        @yield('content')
    </main>

</div>

@stack('scripts')
</body>
</html>