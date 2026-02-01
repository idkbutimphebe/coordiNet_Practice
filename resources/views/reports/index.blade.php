@extends('layouts.dashboard')

@section('content')

<div class="p-6 space-y-10 bg-[#F6F8F5] min-h-screen">

    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
                Reports Dashboard
            </h1>
            <p class="text-sm text-[#6B705C] mt-1">
                Visual insights and detailed system reports
            </p>
        </div>

        <!-- BACK BUTTON -->
        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 text-sm rounded-lg
                  border border-[#A1BC98]
                  text-[#3E3F29]
                  hover:bg-[#E3EAD7] transition">
            ← Back
        </a>
    </div>

    <!-- REPORT LIST -->
    <div class="grid grid-cols-1 gap-8 max-w-5xl mx-auto">

        <!-- TOP 10 COORDINATORS -->
        <a href="{{ route('reports.coordinators') }}"
           class="group relative rounded-3xl
                  bg-gradient-to-r from-[#A1BC98] to-[#DDE7D2]
                  p-8 shadow-md
                  hover:shadow-xl hover:-translate-y-1
                  transition-all duration-300">

            <div class="flex items-center gap-8">
                <div class="w-16 h-16 rounded-2xl bg-white
                            flex items-center justify-center shadow">
                    <svg class="w-8 h-8 text-[#3E3F29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l2.286 7.03h7.388
                                 c.969 0 1.371 1.24.588 1.81l-5.976 4.347
                                 2.287 7.03c.3.921-.755 1.688-1.54 1.118
                                 L12 18.347l-5.996 4.915c-.784.57-1.838-.197-1.539-1.118
                                 l2.287-7.03-5.976-4.347c-.784-.57-.38-1.81.588-1.81h7.388l2.287-7.03z"/>
                    </svg>
                </div>

                <div>
                    <h3 class="text-2xl font-bold text-[#3E3F29] mb-1">
                        Top 10 Coordinators
                    </h3>
                    <p class="text-sm text-[#5F6651] mb-4">
                        Highest performing coordinators by bookings
                    </p>

                    <span class="inline-flex items-center gap-2
                                 font-semibold text-sm text-[#3E3F29]">
                        View Rankings
                        <span class="group-hover:translate-x-1 transition">→</span>
                    </span>
                </div>
            </div>
        </a>

        <!-- CLIENTS -->
        <a href="{{ route('reports.clients') }}"
           class="group relative rounded-3xl
                  bg-gradient-to-r from-[#A1BC98] to-[#DDE7D2]
                  p-8 shadow-md
                  hover:shadow-xl hover:-translate-y-1
                  transition-all duration-300">

            <div class="flex items-center gap-8">
                <div class="w-16 h-16 rounded-2xl bg-white
                            flex items-center justify-center shadow">
                    <svg class="w-8 h-8 text-[#3E3F29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M5.121 17.804A9 9 0 1118 12a9 9 0 01-12.879 5.804z"/>
                    </svg>
                </div>

                <div>
                    <h3 class="text-2xl font-bold text-[#3E3F29] mb-1">
                        Clients
                    </h3>
                    <p class="text-sm text-[#5F6651] mb-4">
                        Approved clients and assigned events
                    </p>

                    <span class="inline-flex items-center gap-2
                                 font-semibold text-sm text-[#3E3F29]">
                        View Clients
                        <span class="group-hover:translate-x-1 transition">→</span>
                    </span>
                </div>
            </div>
        </a>

        <!-- RATINGS -->
        <a href="{{ route('reports.ratings') }}"
           class="group relative rounded-3xl
                  bg-gradient-to-r from-[#A1BC98] to-[#DDE7D2]
                  p-8 shadow-md
                  hover:shadow-xl hover:-translate-y-1
                  transition-all duration-300">

            <div class="flex items-center gap-8">
                <div class="w-16 h-16 rounded-2xl bg-white
                            flex items-center justify-center shadow">
                    <svg class="w-8 h-8 text-[#3E3F29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l2.286 7.03h7.388
                                 c.969 0 1.371 1.24.588 1.81l-5.976 4.347
                                 2.287 7.03c.3.921-.755 1.688-1.54 1.118
                                 L12 18.347l-5.996 4.915c-.784.57-1.838-.197-1.539-1.118
                                 l2.287-7.03-5.976-4.347c-.784-.57-.38-1.81.588-1.81h7.388l2.287-7.03z"/>
                    </svg>
                </div>

                <div>
                    <h3 class="text-2xl font-bold text-[#3E3F29] mb-1">
                        Ratings & Feedback
                    </h3>
                    <p class="text-sm text-[#5F6651] mb-4">
                        Client satisfaction and reviews
                    </p>

                    <span class="inline-flex items-center gap-2
                                 font-semibold text-sm text-[#3E3F29]">
                        View Feedback
                        <span class="group-hover:translate-x-1 transition">→</span>
                    </span>
                </div>
            </div>
        </a>

    </div>

</div>

@endsection
