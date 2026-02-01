<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-[#A1BC98] text-gray-900">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#778873] min-h-screen text-white">

        <!-- ADMIN LABEL -->
        <div class="px-4 py-5 border-b border-white/20 flex justify-center">
            <h2 class="text-lg font-semibold tracking-wide">Admin</h2>
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
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                    <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
                    <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line>
                </svg>
                <span>Bookings</span>
            </a>

            <!-- Coordinator -->
            <a href="{{ route('coordinators') }}"
               class="flex items-center gap-3 px-4 py-2 rounded transition
                      {{ request()->routeIs('coordinators*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a4 4 0 00-5-4
                             M9 20H4v-2a4 4 0 015-4
                             m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Coordinator</span>
            </a>

            <!-- Pending Coordinators -->
            <a href="{{ route('pending') }}"
               class="flex items-center gap-3 px-4 py-2 rounded transition
                      {{ request()->routeIs('pending') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Pending Coordinators</span>
            </a>

            <!-- Reports -->
            <div x-data="{ open: {{ request()->is('reports*') ? 'true' : 'false' }} }" class="relative">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full gap-3 px-4 py-2 rounded transition
                               {{ request()->is('reports*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/20' }}">
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
                    <svg :class="{'rotate-90': open}" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Submenu -->
                <div x-show="open" class="mt-1 pl-10 flex flex-col gap-1 text-sm" x-cloak>
                    <a href="{{ route('reports.topcoordinators') }}"
                       class="px-2 py-1 rounded hover:bg-white/20 {{ request()->routeIs('reports.topcoordinators') ? 'bg-white/20 font-semibold' : '' }}">
                       Top 10 Coordinators
                    </a>
                    <a href="{{ route('reports.coordinators') }}"
                       class="px-2 py-1 rounded hover:bg-white/20 {{ request()->routeIs('reports.coordinators') ? 'bg-white/20 font-semibold' : '' }}">
                       List of Coordinators
                    </a>
                    <a href="{{ route('reports.clients') }}"
                       class="px-2 py-1 rounded hover:bg-white/20 {{ request()->routeIs('reports.clients') ? 'bg-white/20 font-semibold' : '' }}">
                       List of Clients
                    </a>
                    <a href="{{ route('reports.bookings') }}"
                       class="px-2 py-1 rounded hover:bg-white/20 {{ request()->routeIs('reports.bookings') ? 'bg-white/20 font-semibold' : '' }}">
                       List of Bookings
                    </a>
                    <a href="{{ route('reports.income') }}"
                       class="px-2 py-1 rounded hover:bg-white/20 {{ request()->routeIs('reports.income') ? 'bg-white/20 font-semibold' : '' }}">
                       Income per Coordinator
                    </a>
                    <a href="{{ route('reports.ratings') }}"
                       class="px-2 py-1 rounded hover:bg-white/20 {{ request()->routeIs('reports.ratings') ? 'bg-white/20 font-semibold' : '' }}">
                       Client Ratings & Feedback
                    </a>
                </div>
            </div>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 w-full px-4 py-2 rounded hover:bg-red-500/30 text-left transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M17 16l4-4m0 0l-4-4m4 4H7 M7 4h6v4"/>
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
