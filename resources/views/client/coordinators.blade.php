@extends('layouts.client')

@section('content')
<div class="space-y-8">

    @if(session('success'))
        <div class="p-4 text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 text-red-700 bg-red-100 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- PAGE HEADER -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
            Event Coordinators
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            Search, compare, and book professional event coordinators
        </p>
    </div>

    <!-- SEARCH BAR + FILTER -->
    <form method="GET" action="{{ url()->current() }}" class="flex flex-col md:flex-row gap-3 relative">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search by name, location, or email..."
               class="w-full md:flex-1 px-4 py-3 rounded-lg border border-[#A1BC98] focus:ring-2 focus:ring-[#778873] focus:outline-none text-sm">

        <div class="flex gap-3 relative">
            <!-- FILTER DROPDOWN -->
            <div class="relative">
                <details id="filterDropdown" class="group">
                    <summary class="flex items-center gap-2 px-6 py-3 rounded-lg bg-[#3E3F29] text-white font-semibold text-sm cursor-pointer hover:opacity-90 transition list-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2
                                     a1 1 0 01-.293.707L15 12.414V19
                                     a1 1 0 01-.553.894l-4 2A1
                                     1 0 019 21v-8.586L3.293
                                     6.707A1 1 0 013 6V4z"/>
                        </svg>
                        <span id="filterLabel">{{ request('event_type') ?? 'All Events' }}</span>
                    </summary>

                    <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-20">
                        @foreach(['Wedding','Birthday','Others'] as $type)
                        <label class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-[#F6F8F5] cursor-pointer {{ $loop->first ? 'rounded-t-xl' : ($loop->last ? 'rounded-b-xl' : '') }}">
                            <input type="radio" name="event_type" value="{{ $type }}" class="hidden peer"
                                   {{ request('event_type') === $type ? 'checked' : '' }}
                                   onclick="selectFilter('{{ $type }}')">
                            <span class="w-4 h-4 rounded-full border border-gray-400 peer-checked:bg-[#3E3F29]"></span>
                            {{ $type === 'Others' ? 'Other Events' : $type }}
                        </label>
                        @endforeach
                    </div>
                </details>
            </div>

            <button type="submit" class="px-8 py-3 rounded-lg bg-[#3E3F29] text-white font-semibold text-sm hover:opacity-90 transition">Search</button>
        </div>
    </form>

    <script>
        function selectFilter(label) {
            document.getElementById('filterLabel').innerText = label;
            document.getElementById('filterDropdown').removeAttribute('open');
        }
    </script>

    @php
        $query = \App\Models\User::where('role','coordinator');

        if(request('search')) {
            $query->where(function($q){
                $q->where('name','like','%'.request('search').'%')
                  ->orWhere('location','like','%'.request('search').'%')
                  ->orWhere('email','like','%'.request('search').'%');
            });
        }

        if(request('event_type')) {
            $query->whereJsonContains('services', request('event_type'));
        }

        $coordinators = $query->orderByDesc('rate')->get();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($coordinators as $coordinator)
        @php
            $avgRating = \App\Models\Reviews::where('coordinator_id',$coordinator->id)->avg('rating') ?? 0;
            $avgRating = number_format($avgRating,1);
        @endphp

        <div class="bg-white rounded-2xl p-5 border border-[#A1BC98]/40 shadow-sm hover:shadow-xl transition-all hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[#A1BC98] flex items-center justify-center font-bold text-[#3E3F29]">
                            {{ strtoupper(substr($coordinator->name,0,1).substr(explode(' ',$coordinator->name)[1] ?? '',0,1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-[#3E3F29]">{{ $coordinator->name }}</p>
                            <p class="text-xs text-gray-500">⭐ {{ $avgRating }} rating</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">{{ $coordinator->email }}</p>
                </div>
                <span class="px-3 py-1 text-xs rounded-full bg-[#778873]/15 text-[#3E3F29] font-semibold">General</span>
            </div>

            <div class="mt-4 text-sm">
                Status:
                <span class="font-medium {{ $coordinator->is_active ? 'text-green-600' : 'text-red-500' }}">
                    {{ $coordinator->is_active ? 'Available' : 'Busy' }}
                </span>
            </div>

            <div class="mt-5 flex gap-3">
                <a href="{{ route('client.coordinators.view',$coordinator->id) }}" class="flex-1 text-center py-2 rounded-lg border border-[#778873] text-[#778873] text-sm font-medium hover:bg-[#778873]/10 transition">View</a>

                <button type="button" class="flex-1 text-center py-2 rounded-lg bg-[#3E3F29] text-white text-sm font-semibold hover:opacity-90 transition"
                        onclick="document.getElementById('bookingModal-{{ $coordinator->id }}').classList.remove('hidden')">Book</button>
            </div>
        </div>
<!-- BOOKING MODAL -->
<!-- BOOKING MODAL -->
<div id="bookingModal-{{ $coordinator->id }}" 
     class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center transition-opacity duration-300">

    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md relative transform transition-transform duration-300 scale-95">
        
        <!-- CLOSE BUTTON -->
        <button type="button" onclick="document.getElementById('bookingModal-{{ $coordinator->id }}').classList.add('hidden')" 
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors text-2xl font-bold">
            &times;
        </button>

        <!-- MODAL HEADER -->
        <h2 class="text-2xl font-extrabold text-[#3E3F29] mb-4 text-center">
            Book an Event with <span class="text-[#778873]">{{ $coordinator->name }}</span>
        </h2>

        <p class="text-gray-600 text-sm mb-6 text-center">
            Fill out the details below to schedule your event.
        </p>

        <!-- BOOKING FORM -->
        <form method="POST" action="{{ route('client.bookings.store') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="coordinator_id" value="{{ $coordinator->id }}">

            <!-- Choose Event Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Choose Event Type
                </label>

                @php
                    $availableCoordinatorEventTypes = is_array($coordinator->event_types ?? null)
                        ? ($coordinator->event_types ?? [])
                        : [];
                @endphp

                <select name="event_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg
                       focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">
                    <option value="">-- Select Event (Optional) --</option>

                    @forelse($availableCoordinatorEventTypes as $eventType)
                        <option value="{{ $eventType }}"
                            {{ old('event_type') == $eventType ? 'selected' : '' }}>
                            {{ $eventType }}
                        </option>
                    @empty
                        <option value="" disabled>No event types available</option>
                    @endforelse
                </select>

                @error('event_type')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Event Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                <input type="date" name="event_date" 
                       value="{{ old('event_date') }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">
                @error('event_date')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Time -->
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                    <input type="time" name="start_time" 
                           value="{{ old('start_time') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">
                    @error('start_time')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                    <input type="time" name="end_time" 
                           value="{{ old('end_time') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">
                    @error('end_time')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="note" rows="3" placeholder="Additional details..." 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">{{ old('note') }}</textarea>
                @error('note')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" 
                    class="w-full bg-[#3E3F29] hover:bg-[#556644] text-white font-semibold py-2 rounded-xl shadow-sm transition-colors">
                Book Event
            </button>
        </form>
    </div>
</div>


        @endforeach
    </div>
</div>
@endsection
