@extends('layouts.client')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-12">

    {{-- Top Navigation / Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29]">
                Booking <span class="text-[#778873]">Details</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Manage your event schedule and logistics.</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Edit Button (Only if pending) --}}
            @if($booking->status === 'pending')
                <button onclick="document.getElementById('editBookingModal').classList.remove('hidden')"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#3E3F29] text-white font-semibold text-sm hover:bg-[#556644] transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                    Edit Details
                </button>
            @endif

            <a href="{{ route('client.bookings.index') }}" 
               class="group flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-600 font-semibold text-sm hover:border-[#A1BC98] hover:text-[#3E3F29] transition-all shadow-sm hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                Back
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- LEFT COLUMN: Client Card --}}
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 relative overflow-hidden text-center group">
                {{-- Decorative bg --}}
                <div class="absolute top-0 left-0 w-full h-24 bg-[#F6F8F5]"></div>
                
                {{-- Avatar --}}
                <div class="relative mx-auto w-24 h-24 rounded-full bg-[#3E3F29] text-white text-2xl font-serif flex items-center justify-center border-4 border-white shadow-lg mb-4 z-10">
                    {{ strtoupper(substr($booking->client->name ?? $booking->client->first_name ?? 'C', 0, 1)) }}
                </div>

                <h2 class="text-xl font-bold text-[#3E3F29]">
                    {{ $booking->client->name ?? (($booking->client->first_name ?? '') . ' ' . ($booking->client->last_name ?? '')) }}
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
                            <p class="text-sm font-medium text-[#3E3F29] truncate">{{ $booking->client->email ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Phone Display --}}
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-[#F9FAFB] border border-gray-100 group-hover:border-[#A1BC98]/30 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#778873] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Phone</p>
                            <p class="text-sm font-medium text-[#3E3F29]">
                                {{ optional($booking->client)->phone_number ?? '-' }}
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
                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Booked</span>
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
                                {{ $booking->event_type ?? 'Event' }}
                            </span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-serif text-[#F6F8F5] leading-tight">
                            {{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('F d, Y') : 'Date TBD' }}
                        </h2>
                        <p class="text-[#A1BC98] mt-2 text-sm font-medium tracking-wide">
                            {{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('l') : '' }} • 
                            @if($booking->start_time && $booking->end_time)
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            @else
                                Time TBD
                            @endif
                        </p>
                    </div>

                    <div class="text-left md:text-right">
                        @php $daysAway = $booking->event_date ? (int)now()->diffInDays(\Carbon\Carbon::parse($booking->event_date), false) : null; @endphp
                        @if($daysAway !== null)
                            @if($daysAway > 0)
                                <div class="text-5xl md:text-6xl font-bold text-[#A1BC98] tracking-tighter">{{ $daysAway }}</div>
                                <div class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">Days To Go</div>
                            @elseif($daysAway === 0)
                                <div class="text-5xl md:text-6xl font-bold text-[#A1BC98] tracking-tighter">Today</div>
                                <div class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">Happening Now</div>
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
                                {{ $booking->event_type ?? $booking->event_name ?? 'N/A' }}
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
                            <h4 class="font-bold text-[#3E3F29]">Details</h4>
                            <p class="text-xs text-gray-400">Package & Notes</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Service Package</label>
                            <p class="text-sm font-semibold text-[#3E3F29] mt-1">{{ $booking->package ?? 'Standard Package' }}</p>
                        </div>
                        <div class="h-px bg-gray-100 w-full"></div>
                        <div>
                            <label class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Client Notes</label>
                            <p class="text-sm text-gray-500 mt-1 italic line-clamp-3">
                                {{ $booking->note ?? $booking->notes ?? 'No notes provided.' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>

             {{-- Coordinator Info --}}
             <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mt-6 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#F6F8F5] text-[#3E3F29] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-[#3E3F29] text-sm">Assigned Coordinator</h4>
                    <p class="text-sm text-gray-500">{{ $booking->coordinator->user->name ?? $booking->coordinator->name ?? 'Not yet assigned' }}</p>
                </div>
            </div>

            {{-- Rating Section --}}
            @if($booking->status === 'completed' || (\Carbon\Carbon::parse($booking->event_date)->isPast()))
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mt-6">
                    <h4 class="text-[#3E3F29] font-bold mb-3">Rate Your Experience</h4>
                    <form method="POST" action="{{ route('client.ratings.store') }}">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <div class="flex flex-row-reverse justify-end gap-1 mb-4">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="peer hidden">
                                <label for="star{{ $i }}" class="cursor-pointer text-3xl text-gray-300 hover:text-yellow-400 peer-checked:text-yellow-400 transition">★</label>
                            @endfor
                        </div>
                        <textarea name="feedback" rows="3" placeholder="Feedback..." class="w-full rounded-xl p-3 bg-[#F6F8F5] text-sm text-[#3E3F29] focus:outline-none"></textarea>
                        <button type="submit" class="mt-4 px-5 py-2 rounded-lg text-sm bg-[#3E3F29] text-white hover:bg-[#556644] transition">Submit Review</button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- ========================================== --}}
{{--        EDIT BOOKING MODAL                  --}}
{{-- ========================================== --}}

<div id="editBookingModal" 
     class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center transition-opacity duration-300">

    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md relative transform transition-transform duration-300 scale-95">
        
        <button type="button" onclick="document.getElementById('editBookingModal').classList.add('hidden')" 
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors text-2xl font-bold">
            &times;
        </button>

        <h2 class="text-2xl font-extrabold text-[#3E3F29] mb-4 text-center">
            Edit Booking Details
        </h2>

        {{-- FORM START --}}
        <form method="POST" action="{{ route('client.bookings.update', $booking->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Dynamic Event Type Selection --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Choose Event Type
                </label>
                @php
                    // Safely get coordinator types, even if coordinator is null
                    $typesRaw = optional($booking->coordinator)->event_types; 
                    
                    // Decode if string, else use as array, else empty
                    if (is_string($typesRaw)) {
                        $availableTypes = json_decode($typesRaw, true) ?? [];
                    } elseif (is_array($typesRaw)) {
                        $availableTypes = $typesRaw;
                    } else {
                        $availableTypes = [];
                    }

                    // Fallback defaults if list is empty or no coordinator assigned
                    if (empty($availableTypes)) {
                        $availableTypes = ['Wedding', 'Birthday', 'Corporate', 'Anniversary', 'Christening', 'Other'];
                    }
                @endphp
                
                <select name="event_type" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">
                    <option value="{{ $booking->event_type }}" selected>{{ $booking->event_type }} (Current)</option>
                    @foreach($availableTypes as $type)
                        @if($type !== $booking->event_type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                <input type="date" name="event_date" 
                       value="{{ old('event_date', $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') : '') }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Event Location</label>
                <input type="text" name="location" 
                       value="{{ old('location', $booking->location) }}"
                       required
                       placeholder="e.g. Grand Ballroom, City Hotel, etc."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">
                @error('location')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                    <input type="time" name="start_time" 
                           value="{{ old('start_time', $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                    <input type="time" name="end_time" 
                           value="{{ old('end_time', $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : '') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="note" rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">{{ old('note', $booking->note ?? $booking->notes) }}</textarea>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="button" 
                        onclick="document.getElementById('editBookingModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                
                <button type="submit" 
                        class="flex-1 bg-[#3E3F29] hover:bg-[#556644] text-white font-semibold px-4 py-2 rounded-xl shadow-sm transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection