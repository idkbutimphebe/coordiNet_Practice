@extends('layouts.coordinator')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-12">

    {{-- Top Navigation / Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29]">
                Booking <span class="text-[#778873]">Request</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Review client details and manage status.</p>
        </div>

        {{-- ACTION BUTTONS (Back, Accept, Decline) --}}
        <div class="flex flex-wrap items-center gap-3">
            
            {{-- ONLY SHOW ACTIONS IF PENDING --}}
            @if($booking->status === 'pending')
                {{-- Accept Button --}}
                <form method="POST" action="{{ route('coordinator.bookings.confirm', $booking->id) }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#3E3F29] text-white font-semibold text-sm hover:bg-[#556644] transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Accept
                    </button>
                </form>

                {{-- Decline Button --}}
                <form method="POST" action="{{ route('coordinator.bookings.cancel', $booking->id) }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-red-200 text-red-600 font-semibold text-sm hover:bg-red-50 transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Decline
                    </button>
                </form>
            @endif

            {{-- Back Button --}}
            <a href="{{ route('coordinator.bookings') }}" 
               class="group flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-600 font-semibold text-sm hover:border-[#A1BC98] hover:text-[#3E3F29] transition-all shadow-sm hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- LEFT COLUMN: Client Card --}}
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 relative overflow-hidden text-center group">
                {{-- Decorative bg --}}
                <div class="absolute top-0 left-0 w-full h-24 bg-[#F6F8F5]"></div>
                
                {{-- Avatar --}}
                <div class="relative mx-auto w-24 h-24 rounded-full bg-[#A1BC98] text-white text-3xl font-serif flex items-center justify-center border-4 border-white shadow-lg mb-4 z-10">
                    {{ strtoupper(substr($booking->client->name ?? 'C', 0, 1)) }}
                </div>

                <h2 class="text-xl font-bold text-[#3E3F29]">
                    {{ $booking->client->name ?? 'Client Name' }}
                </h2>
                
                <div class="mt-3 flex justify-center">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                        {{ $booking->status === 'pending' ? 'bg-[#A1BC98] text-[#3E3F29]' : '' }}
                        {{ $booking->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                <div class="mt-8 space-y-4 text-left">
                    {{-- Email --}}
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-[#F9FAFB] border border-gray-100 group-hover:border-[#A1BC98]/30 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#778873] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Email</p>
                            <p class="text-sm font-medium text-[#3E3F29] truncate">{{ $booking->client->email ?? 'N/A' }}</p>
                        </div>
                    </div>

                    {{-- Phone Display --}}
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-[#F9FAFB] border border-gray-100 group-hover:border-[#A1BC98]/30 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#778873] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Phone</p>
                            {{-- UPDATED: Directly calls the phone_number field from the client object --}}
                            <p class="text-sm font-medium text-[#3E3F29]">
                                {{ $booking->client->phone_number ?? 'No number provided' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100 flex justify-between items-center px-2">
                    <div class="text-center">
                        <span class="block text-lg font-bold text-[#3E3F29]">{{ $booking->id }}</span>
                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">ID</span>
                    </div>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div class="text-center">
                        <span class="block text-lg font-bold text-[#3E3F29]">{{ $booking->created_at ? $booking->created_at->format('M d') : '-' }}</span>
                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Requested</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Event Info --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Hero Event Card --}}
            <div class="bg-[#3E3F29] rounded-2xl p-8 md:p-10 text-white relative overflow-hidden shadow-xl">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-[#778873] rounded-full blur-3xl opacity-40"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-56 h-56 bg-[#A1BC98] rounded-full blur-3xl opacity-20"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#A1BC98] text-[#3E3F29] uppercase tracking-wider">
                                {{ $booking->event->event_type ?? $booking->event_name ?? 'Event' }}
                            </span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-serif text-[#F6F8F5] leading-tight">
                            {{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('F d, Y') : 'Date TBD' }}
                        </h2>
                        <p class="text-[#A1BC98] mt-2 text-sm font-medium tracking-wide">
                            {{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('l') : '' }} • 
                            @if($booking->start_time && $booking->end_time)
                                {{ $booking->start_time }} - {{ $booking->end_time }}
                            @else
                                Time TBD
                            @endif
                        </p>
                    </div>

                    <div class="text-left md:text-right">
                        @php $daysAway = $booking->event_date ? (int)now()->diffInDays(\Carbon\Carbon::parse($booking->event_date), false) : null; @endphp
                        @if($daysAway !== null)
                            @if($daysAway >= 0)
                                <div class="text-5xl md:text-6xl font-bold text-[#A1BC98] tracking-tighter">{{ $daysAway }}</div>
                                <div class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">Days To Go</div>
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
                
                {{-- LOGISTICS BOX --}}
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
                                {{ $booking->event->event_type ?? $booking->event_name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="h-px bg-gray-100 w-full"></div>
                        
                        <div>
                            <label class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Venue / Location</label>
                            <p class="text-sm font-semibold text-[#3E3F29] mt-1">
                                {{ $booking->location ?? 'Location to be determined' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- DETAILS BOX --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:border-[#A1BC98] transition-colors duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-[#F6F8F5] text-[#3E3F29] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#3E3F29]">Notes</h4>
                            <p class="text-xs text-gray-400">Client Requirements</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Additional Information</label>
                            <p class="text-sm text-gray-600 mt-2 leading-relaxed italic">
                                @if(!empty($booking->note))
                                    "{{ $booking->note }}"
                                @else
                                    No additional notes provided.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection