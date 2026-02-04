@extends('layouts.dashboard')

@section('content')

<div class="max-w-5xl mx-auto space-y-12">

    <div class="flex flex-col md:flex-row items-end justify-between gap-6 border-b border-[#8A9A5B]/20 pb-8">
        <div>
            <span class="text-xs uppercase tracking-[0.3em] text-[#8A9A5B] font-semibold">Administrative Portal</span>
            <h1 class="text-5xl font-serif italic text-[#2D2E22] mt-2">
                Client Profile
            </h1>
        </div>
        
        <a href="{{ route('bookings') }}" 
           class="flex items-center gap-3 px-8 py-3 rounded-full border border-[#2D2E22] text-[#2D2E22] font-medium text-sm hover:bg-[#2D2E22] hover:text-white transition-all duration-500 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            <span class="tracking-widest uppercase text-[10px]">Back to Archive</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

        <!-- Client Card -->
        <div class="lg:col-span-4 space-y-8">
            <div class="relative bg-white p-10 rounded-[3rem] shadow-sm border border-[#8A9A5B]/10 overflow-hidden text-center">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#F9F8F4] rounded-full -mr-16 -mt-16"></div>

                <!-- Client Avatar -->
                <div class="mx-auto w-24 h-24 rounded-full bg-[#8A9A5B]/10 border border-[#8A9A5B]/20 text-[#8A9A5B] text-2xl font-serif flex items-center justify-center italic mb-6">
                    {{ strtoupper(substr(optional($booking->client)->first_name ?? 'C', 0, 1) . substr(optional($booking->client)->last_name ?? '', 0, 1)) }}
                </div>

                <h2 class="text-3xl font-serif text-[#2D2E22]">
                    {{ optional($booking->client)->first_name ?? 'Client' }} 
                    <span class="block italic opacity-70 text-2xl">{{ optional($booking->client)->last_name ?? '' }}</span>
                </h2>

                <div class="mt-4 flex flex-col items-center gap-3">
                    <span class="px-4 py-1 rounded-full bg-[#F9F8F4] text-[#8A9A5B] text-[10px] uppercase tracking-tighter font-bold border border-[#8A9A5B]/20">
                        {{ optional($booking->client)->status ?? 'New Client' }}
                    </span>
                    <span class="text-xs italic text-gray-400">Status: {{ optional($booking->client)->status ?? 'Pending Review' }}</span>
                </div>

                <div class="mt-10 pt-8 border-t border-gray-50 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[9px] uppercase tracking-widest text-gray-400 mb-1">Bookings</p>
                        <p class="text-xl font-medium">{{ optional($booking->client)->bookings?->count() ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] uppercase tracking-widest text-gray-400 mb-1">Portfolio</p>
                        <p class="text-xl font-medium">N/A</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Info -->
        <div class="lg:col-span-8 space-y-8">

            <!-- Event Card -->
            <div class="bg-[#2D2E22] rounded-[3rem] p-10 text-white flex justify-between items-center shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <p class="uppercase text-[10px] tracking-[0.4em] text-[#8A9A5B] mb-4 font-bold">The Main Event</p>
                    <h3 class="text-5xl font-serif">{{ $booking->event_date?->format('F d, Y') ?? 'TBD' }}</h3>
                </div>
                
                <div class="relative z-10 text-right">
                    @php
                        $daysAway = $booking->event_date ? now()->diffInDays($booking->event_date) : '-';
                    @endphp
                    <div class="text-6xl font-serif italic text-[#8A9A5B]/40">{{ $daysAway }}</div>
                    <div class="text-[10px] uppercase tracking-widest">Days Away</div>
                </div>
                
                <div class="absolute inset-0 opacity-10 pointer-events-none uppercase text-[8rem] font-serif -rotate-12 translate-y-10">
                    Date
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Communication -->
                <div class="bg-white p-8 rounded-[2.5rem] border border-[#8A9A5B]/10 hover:border-[#8A9A5B]/40 transition-colors duration-500">
                    <h4 class="text-[11px] uppercase tracking-[0.3em] text-[#8A9A5B] font-bold mb-6 flex items-center gap-3">
                        <span class="w-1 h-1 rounded-full bg-[#8A9A5B]"></span>
                        Communication
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[9px] uppercase text-gray-400 tracking-wider mb-1">Email Address</label>
                            <p class="text-sm font-medium border-b border-gray-50 pb-2">{{ optional($booking->client)->email ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-[9px] uppercase text-gray-400 tracking-wider mb-1">Phone Number</label>
                            <p class="text-sm font-medium">{{ optional($booking->client)->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Logistics -->
                <div class="bg-white p-8 rounded-[2.5rem] border border-[#8A9A5B]/10 hover:border-[#8A9A5B]/40 transition-colors duration-500">
                    <h4 class="text-[11px] uppercase tracking-[0.3em] text-[#8A9A5B] font-bold mb-6 flex items-center gap-3">
                        <span class="w-1 h-1 rounded-full bg-[#8A9A5B]"></span>
                        Logistics
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[9px] uppercase text-gray-400 tracking-wider mb-1">Event Category</label>
                            <p class="text-sm font-medium italic border-b border-gray-50 pb-2">{{ $booking->category ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-[9px] uppercase text-gray-400 tracking-wider mb-1">Primary Location</label>
                            <p class="text-sm font-medium">{{ $booking->location ?? '-' }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
