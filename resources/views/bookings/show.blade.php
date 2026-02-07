@extends('layouts.dashboard')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-12">

    {{-- Top Navigation / Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-extrabold text-[#3E3F29]">
                    Client's Details<span class="text-[#778873]">
                </h1>
            </div>
            <p class="text-sm text-gray-500 mt-1">Viewing client details and event logistics.</p>
        </div>

        {{-- UPDATE: Fixed route to match your 'bookings' name --}}
        <a href="{{ route('bookings') }}" 
           class="group flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-600 font-semibold text-sm hover:border-[#A1BC98] hover:text-[#3E3F29] transition-all shadow-sm hover:shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
            Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- LEFT COLUMN: Client Card (Read Only) --}}
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 relative overflow-hidden text-center group">
                {{-- Decorative bg circle --}}
                <div class="absolute top-0 left-0 w-full h-24 bg-[#F6F8F5]"></div>
                
                {{-- Avatar / Initials --}}
                <div class="relative mx-auto w-24 h-24 rounded-full bg-[#3E3F29] text-white text-2xl font-serif flex items-center justify-center border-4 border-white shadow-lg mb-4 z-10">
                    {{ strtoupper(substr(optional($booking->client)->first_name ?? 'C', 0, 1) . substr(optional($booking->client)->last_name ?? '', 0, 1)) }}
                </div>

                {{-- Name --}}
                <h2 class="text-xl font-bold text-[#3E3F29]">
                    {{ optional($booking->client)->first_name ?? 'Client' }} {{ optional($booking->client)->last_name ?? '' }}
                </h2>
                <p class="text-xs text-gray-400 mt-1">Client ID: {{ $booking->client_id }}</p>
                
                {{-- Status Badge --}}
                <div class="mt-3 flex justify-center">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                        {{ (optional($booking->client)->status == 'Active') ? 'bg-[#A1BC98]/20 text-[#5a6b54]' : 'bg-gray-100 text-gray-500' }}">
                        {{ optional($booking->client)->status ?? 'Pending Review' }}
                    </span>
                </div>

                {{-- Contact Details List --}}
                <div class="mt-8 space-y-4 text-left">
                    {{-- Email --}}
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-[#F9FAFB] border border-gray-100 group-hover:border-[#A1BC98]/30 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#778873] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Email</p>
                            <a href="mailto:{{ optional($booking->client)->email }}" class="text-sm font-medium text-[#3E3F29] truncate hover:text-[#778873] transition-colors">
                                {{ optional($booking->client)->email ?? '-' }}
                            </a>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-[#F9FAFB] border border-gray-100 group-hover:border-[#A1BC98]/30 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#778873] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Phone</p>
                            <p class="text-sm font-medium text-[#3E3F29]">
                                {{ $booking->client->phone_number ?? $booking->client->phone ?? $booking->client->contact_number ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Simple Stats --}}
                <div class="mt-6 pt-6 border-t border-gray-100 flex justify-between items-center px-2">
                    <div class="text-center">
                        <span class="block text-lg font-bold text-[#3E3F29]">{{ optional($booking->client)->bookings?->count() ?? 0 }}</span>
                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Total Bookings</span>
                    </div>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div class="text-center">
                        <span class="block text-lg font-bold text-[#3E3F29]">{{ $booking->created_at ? $booking->created_at->format('M Y') : '-' }}</span>
                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Date Booked</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Event Info --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Hero Event Card (Identical Visuals) --}}
            <div class="bg-[#3E3F29] rounded-2xl p-8 md:p-10 text-white relative overflow-hidden shadow-xl">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-[#778873] rounded-full blur-3xl opacity-40"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-56 h-56 bg-[#A1BC98] rounded-full blur-3xl opacity-20"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#A1BC98] text-[#3E3F29] uppercase tracking-wider">
                                {{ $booking->status ?? 'Upcoming Event' }}
                            </span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-serif text-[#F6F8F5] leading-tight">
                            {{-- Safe Date Parsing --}}
                         {{ $booking->event->event_type ?? $booking->event_name ?? 'N/A' }}
                        </h2>
                        <p class="text-[#A1BC98] mt-2 text-sm font-medium tracking-wide">
                            {{ $booking->event_date instanceof \Carbon\Carbon ? $booking->event_date->format('l') : ($booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('l') : '') }} 
                            • 
                            {{-- Safe Time Parsing --}}
                            {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('g:i A') : 'Time TBD' }}
                        </p>
                    </div>

                    <div class="text-left md:text-right">
                        @php
                            $eDate = $booking->event_date instanceof \Carbon\Carbon ? $booking->event_date : ($booking->event_date ? \Carbon\Carbon::parse($booking->event_date) : null);
                            $daysAway = $eDate ? (int)now()->diffInDays($eDate, false) : null;
                        @endphp
                        
                        @if($daysAway !== null)
                            @if($daysAway > 0)
                                <div class="text-5xl md:text-6xl font-bold text-[#A1BC98] tracking-tighter">{{ $daysAway }}</div>
                                <div class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">Days To Go</div>
                            @elseif($daysAway == 0)
                                <div class="text-4xl font-bold text-[#A1BC98]">TODAY</div>
                                <div class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">Event Day</div>
                            @else
                                <div class="text-4xl font-bold text-gray-500">DONE</div>
                                <div class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mt-1">Past Event</div>
                            @endif
                        @else
                            <span class="text-2xl text-gray-400">--</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Logistics Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Logistics Box --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:border-[#A1BC98] transition-colors duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-[#F6F8F5] text-[#3E3F29] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#3E3F29]">Logistics</h4>
                            <p class="text-xs text-gray-400">Venue & Type</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Event Type</label>
                            <p class="text-sm font-semibold text-[#3E3F29] mt-1">
                                {{ $booking->event_type ?? 'Not Specified' }}
                            </p>
                        </div>
                        <div class="h-px bg-gray-100 w-full"></div>
                        <div>
                            <label class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Location / Venue</label>
                            <p class="text-sm font-semibold text-[#3E3F29] mt-1">{{ $booking->location ?? 'To Be Decided' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Package / Requirements Box --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:border-[#A1BC98] transition-colors duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-[#F6F8F5] text-[#3E3F29] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#3E3F29]">Details</h4>
                            <p class="text-xs text-gray-400">Package & Notes</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Service Type</label>
                            <p class="text-sm font-semibold text-[#3E3F29] mt-1">{{ $booking->package ?? 'Standard Package' }}</p>
                        </div>
                         <div class="h-px bg-gray-100 w-full"></div>
                        <div>
                            <label class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Client Notes</label>
                            <div class="bg-[#F9FAFB] p-3 rounded-lg border border-gray-100 mt-1">
                                <p class="text-sm text-gray-600 italic">
                                    {{ $booking->note ?? $booking->notes ?? 'No notes provided by client.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection