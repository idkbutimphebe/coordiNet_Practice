<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- 👇 ADD THIS LINE --}}
    @stack('styles')
</head>


<!-- MAIN PAGE BACKGROUND -->
<body class="bg-[#A1BC98] text-gray-900">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#778873] min-h-screen text-white">

<!-- ADMIN LABEL -->
<div class="px-4 py-5 border-b border-white/20 flex justify-center">
    <h2 class="text-lg font-semibold tracking-wide">
        Admin
    </h2>
</div>

        <nav class="p-4 space-y-2">

<!-- Dashboard -->
<a href="{{ route('dashboard') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
          {{ request()->routeIs('dashboard') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">

    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M3 12l2-2 7-7 7 7M5 10v10h14V10"/>
    </svg>

    <span>Dashboard</span>
</a>


<!-- Bookings -->
<a href="{{ route('bookings') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
          {{ request()->routeIs('bookings*') ? 'bg-white/20 font-semibold text-white' : 'hover:bg-white/20' }}">

    <!-- Calendar / Booking Icon -->
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
        <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
        <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
        <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line>
    </svg>

    <span>Bookings</span>
</a>

<div x-data="{ open: {{ request()->routeIs('coordinators*') ? 'true' : 'false' }} }">

    <div class="flex items-center justify-between px-4 py-2 rounded
        {{ request()->routeIs('coordinators*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">

        <!-- MAIN LINK (INDEX) -->
        <a href="{{ route('coordinators') }}"
           class="flex items-center gap-3 flex-1">

            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      d="M17 20h5v-2a4 4 0 00-5-4
                         M9 20H4v-2a4 4 0 015-4
                         m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>

            <span>Coordinators</span>
        </a>

        <!-- DROPDOWN TOGGLE -->
        <button @click="open = !open" class="ml-2">
            <svg class="w-4 h-4 transition-transform"
                 :class="open ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
    </div>

    <!-- SUB MENU -->
    <div x-show="open" x-transition class="ml-10 mt-2 space-y-1">

        <a href="{{ route('coordinators.event', 'birthday') }}"
           class="block px-3 py-1.5 rounded text-sm hover:bg-white/20">
            Birthday
        </a>

        <a href="{{ route('coordinators.event', 'wedding') }}"
           class="block px-3 py-1.5 rounded text-sm hover:bg-white/20">
            Wedding
        </a>

        <a href="{{ route('coordinators.event', 'others') }}"
           class="block px-3 py-1.5 rounded text-sm hover:bg-white/20">
            Others
        </a>

    </div>
</div>


<a href="{{ route('reports') }}"
   class="flex items-center gap-3 px-4 py-2 rounded transition
          {{ request()->routeIs('reports*') 
              ? 'bg-white/20 font-semibold' 
              : 'hover:bg-white/20' }}">

    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M3 3v18h18
                 M9 17V9
                 M13 17V5
                 M17 17v-7"/>
    </svg>

    <span>Reports</span>
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
    <main class="flex-1 p-8 bg-[#A1BC98]">
        @yield('content')
    </main>

</div>
@stack('scripts')
</body>
</html>
