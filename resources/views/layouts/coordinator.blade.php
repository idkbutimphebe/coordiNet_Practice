<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coordinator</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#A1BC98] text-gray-900">

<div class="flex min-h-screen w-full">

    <aside class="w-64 bg-[#778873] text-white flex-shrink-0">

<div class="px-4 py-5 border-b border-white/20 flex justify-center">
    <h2 class="text-lg font-semibold tracking-wide">
        Coordinator
    </h2>
</div>

        <nav class="p-4 space-y-2">
<a href="{{ route('coordinator.dashboard') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
   {{ request()->routeIs('coordinator.dashboard') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M3 12l2-2 7-7 7 7M5 10v10h14V10"/>
    </svg>
    <span>Dashboard</span>
</a>

<a href="{{ route('coordinator.bookings') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
   {{ request()->routeIs('coordinator.bookings*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"/>
        <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"/>
        <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"/>
        <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"/>
    </svg>
    <span>Bookings</span>
</a>

<a href="{{ route('coordinator.schedule') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
   {{ request()->routeIs('coordinator.schedule') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <span>Schedule</span>
</a>

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

<a href="{{ route('coordinator.income') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
   {{ request()->routeIs('coordinator.income') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7H14a3.5 3.5 0 010 7H6"/>
    </svg>
    <span>Income / Payments</span>
</a>

<!-- Reports -->
<div x-data="{ open: {{ request()->is('coordinator/reports*') ? 'true' : 'false' }} }" class="relative">

    <button @click="open = !open"
        class="flex items-center justify-between w-full gap-3 px-4 py-2 rounded transition
               {{ request()->is('coordinator/reports*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">

        <div class="flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      d="M3 3v18h18
                         M9 17V9
                         M13 17V5
                         M17 17v-7"/>
            </svg>
            <span>Reports</span>
        </div>

        <svg :class="{ 'rotate-90': open }"
             class="w-3 h-3 transition-transform"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    <!-- Submenu -->
    <div x-show="open" x-cloak class="mt-1 pl-10 flex flex-col gap-1 text-sm">

        <a href="{{ route('coordinator.reports.clients') }}"
           class="px-2 py-1 rounded hover:bg-white/20
                  {{ request()->routeIs('coordinator.reports.clients') ? 'bg-white/20 font-semibold' : '' }}">
            List of Clients
        </a>

        <a href="{{ route('coordinator.reports.bookings') }}"
           class="px-2 py-1 rounded hover:bg-white/20
                  {{ request()->routeIs('coordinator.reports.bookings') ? 'bg-white/20 font-semibold' : '' }}">
            List of Bookings
        </a>

        <a href="{{ route('coordinator.reports.income') }}"
           class="px-2 py-1 rounded hover:bg-white/20
                  {{ request()->routeIs('coordinator.reports.income') ? 'bg-white/20 font-semibold' : '' }}">
            Income
        </a>

        <a href="{{ route('coordinator.reports.feedback') }}"
           class="px-2 py-1 rounded hover:bg-white/20
                  {{ request()->routeIs('coordinator.reports.feedback') ? 'bg-white/20 font-semibold' : '' }}">
            Client Ratings & Feedback
        </a>

    </div>
</div>


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
<main class="flex-1 min-w-0 bg-[#A1BC98] p-8 overflow-x-auto">
        @yield('content')
    </main>

</div>

@stack('scripts')
</body>
</html>