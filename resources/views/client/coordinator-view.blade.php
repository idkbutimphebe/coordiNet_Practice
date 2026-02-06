@extends('layouts.client')

@section('content')

@php
    // Get the User model associated with this coordinator profile
    $coordinatorUser = $coordinator->user; 

    // Average rating and total reviews
    $averageRating = \App\Models\Reviews::where('coordinator_id', $coordinator->id)->avg('rating') ?? 0;
    $averageRating = number_format($averageRating, 1);

    $totalReviews = \App\Models\Reviews::where('coordinator_id', $coordinator->id)->count();

    // Fix: Get services from coordinator
    $servicesRaw = $coordinator->services ?? [];
    $servicesArray = is_string($servicesRaw) 
        ? json_decode($servicesRaw, true) 
        : ($servicesRaw ?? []);

    // Portfolio default
    $portfolio = $coordinator->portfolio ?? [];

    // CHECK: Has the current client completed an event with this coordinator?
    $hasCompletedBooking = \App\Models\Booking::where('client_id', \Illuminate\Support\Facades\Auth::id())
        ->where('coordinator_id', $coordinator->id)
        ->where(function($query) {
            $query->where('status', 'completed')
                  ->orWhereDate('event_date', '<', now());
        })
        ->exists();
@endphp

<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

    <div class="lg:col-span-2 space-y-8">

        <div class="bg-white rounded-3xl p-6 flex items-center gap-6 shadow-sm">
            
            <img src="{{ $coordinatorUser->avatar ? asset('storage/'.$coordinatorUser->avatar) : 'https://i.pravatar.cc/150?img=12' }}" 
                 class="w-24 h-24 rounded-full border-4 border-[#A1BC98] object-cover">

            <div class="flex-1">
                <h1 class="text-2xl font-extrabold text-[#3E3F29]">
                    {{ $coordinatorUser->name ?? $coordinator->coordinator_name }}
                </h1>

                <p class="text-sm text-gray-600">
                    {{ count($servicesArray) ? implode(' & ', $servicesArray) : 'General' }} Event Coordinator
                </p>

                @if($hasCompletedBooking)
                    <div class="flex items-center gap-2 mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-xl {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}">
                                ★
                            </span>
                        @endfor
                        <span class="text-sm text-gray-500">
                            {{ $averageRating }} ({{ $totalReviews }} reviews)
                        </span>
                    </div>
                @else
                    <div class="mt-2 text-xs text-gray-400 italic">
                        Ratings visible after completion
                    </div>
                @endif

                <p class="text-xs text-gray-400 mt-1">
                    {{ $coordinatorUser->email ?? 'No email provided' }}
                </p>

                <span class="inline-flex items-center gap-2 mt-3 px-4 py-1.5 
                             text-sm rounded-full bg-[#E9F0E6] 
                             text-[#3E3F29] font-medium">
                    {{ $coordinatorUser->is_active ?? true ? 'Available for Booking' : 'Currently Busy' }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-5">
                Event Designs & Portfolio
            </h2>

            @if(count($portfolio))
                <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                    @foreach($portfolio as $item)
                        @if(!empty($item['image']))
                            <img src="{{ asset('storage/'.$item['image']) }}" 
                                 class="rounded-2xl h-44 w-full object-cover hover:scale-105 transition">
                        @else
                            <div class="rounded-2xl h-44 w-full bg-gray-100 
                                        flex items-center justify-center text-gray-400 text-sm">
                                No image
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl h-44 w-full bg-gray-100 
                            flex items-center justify-center text-gray-400 text-sm">
                    No portfolio uploaded yet
                </div>
            @endif
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-3">
                About the Coordinator
            </h2>

            <p class="text-sm text-gray-600 leading-relaxed">
                {{ $coordinator->bio ?? $coordinatorUser->bio ?? 'No bio provided yet ✨' }}
            </p>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-5">
                Client Reviews
            </h2>

            @php
                $reviews = \App\Models\Reviews::where('coordinator_id', $coordinator->id)
                            ->with('client') 
                            ->latest()
                            ->get();
            @endphp

            @if($reviews->count())
                @foreach($reviews as $review)
                    <div class="border-b last:border-none pb-4 mb-4 last:mb-0">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-[#3E3F29]">
                                {{ $review->client->name ?? 'Anonymous' }}
                            </p>
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">
                                        ★
                                    </span>
                                @endfor
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 mt-2">
                            {{ $review->comment ?? 'No comment provided' }}
                        </p>
                    </div>
                @endforeach
            @else
                <p class="text-gray-400 text-sm italic">No reviews yet ✨</p>
            @endif
        </div>

    </div>

    <div class="space-y-8">

        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-5">
                Services Offered
            </h2>

            <div class="space-y-3 text-sm">
                @if(count($servicesArray))
                    @foreach($servicesArray as $service)
                        <div class="flex items-center gap-3 bg-[#F6F8F5] p-3 rounded-xl">
                            <span class="text-green-600 font-bold">✓</span>
                            <span class="font-medium text-[#3E3F29]">{{ $service }}</span>
                        </div>
                    @endforeach
                @else
                    <div class="flex items-center gap-3 bg-[#F6F8F5] p-3 rounded-xl text-gray-400">
                        ✨ No services listed
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-[#3E3F29] mb-4">
                Availability
            </h2>

            <div class="grid grid-cols-7 gap-2 text-center text-sm">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                    <div class="text-xs text-gray-400">{{ $day }}</div>
                @endforeach

                @for($i = 1; $i <= 31; $i++)
                    @php $booked = false; @endphp
                    <div class="w-10 h-10 flex items-center justify-center rounded-full 
                        {{ $booked ? 'bg-red-200 text-red-600' : 'bg-[#DCE7D8]' }}">
                        {{ $i }}
                    </div>
                @endfor
            </div>
        </div>

        <div class="bg-[#7E8F78] rounded-3xl p-6 text-white shadow-sm">
            <h2 class="font-semibold mb-3">Pricing</h2>
            
            <p class="text-4xl font-extrabold">
                ₱{{ number_format($coordinator->rate ?? $coordinatorUser->rate ?? 0, 2) }}
            </p>
            <p class="text-sm mb-5 opacity-90">per event</p>

            <ul class="space-y-2 text-sm">
                <li>✓ Full coordination</li>
                <li>✓ On-site supervision</li>
                <li>✓ Client support</li>
            </ul>

            {{-- UPDATED: Button now triggers the modal instead of submitting immediately --}}
            <button type="button" 
               onclick="document.getElementById('bookingModal-{{ $coordinator->id }}').classList.remove('hidden')"
               class="block w-full mt-6 py-3 rounded-2xl 
                      bg-white text-[#3E3F29] font-semibold 
                      hover:opacity-90 transition text-center cursor-pointer shadow-lg">
                Book Now
            </button>
        </div>

    </div>
</div>

<div id="bookingModal-{{ $coordinator->id }}" 
     class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center transition-opacity duration-300">

    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md relative transform transition-transform duration-300 scale-95">
        
        <button type="button" onclick="document.getElementById('bookingModal-{{ $coordinator->id }}').classList.add('hidden')" 
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors text-2xl font-bold">
            &times;
        </button>

        <h2 class="text-2xl font-extrabold text-[#3E3F29] mb-4 text-center">
            Book an Event with <span class="text-[#778873]">{{ $coordinator->name }}</span>
        </h2>

        <p class="text-gray-600 text-sm mb-6 text-center">
            Fill out the details below to schedule your event.
        </p>

        <form method="POST" action="{{ route('client.bookings.store') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="coordinator_id" value="{{ $coordinator->id }}">

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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Event Location</label>
                <input type="text" name="location" 
                       value="{{ old('location') }}"
                       required
                       placeholder="e.g. Grand Ballroom, City Hotel, etc."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">
                @error('location')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="note" rows="3" placeholder="Additional details..." 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#778873] focus:border-[#3E3F29]">{{ old('note') }}</textarea>
                @error('note')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" 
                    class="w-full bg-[#3E3F29] hover:bg-[#556644] text-white font-semibold py-2 rounded-xl shadow-sm transition-colors">
                Book Event
            </button>
        </form>
    </div>
</div>

@endsection