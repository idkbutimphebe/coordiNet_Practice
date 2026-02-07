@extends('layouts.dashboard')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $role = $user?->role;

    // Coordinator user
    $coordinatorUser = $coordinator->user;

    // Ratings
    $averageRating = number_format(
        \App\Models\Reviews::where('coordinator_id', $coordinator->id)->avg('rating') ?? 0,
        1
    );

    $totalReviews = \App\Models\Reviews::where('coordinator_id', $coordinator->id)->count();

    // Services
    $servicesRaw = $coordinator->services ?? [];
    $servicesArray = is_string($servicesRaw)
        ? json_decode($servicesRaw, true)
        : ($servicesRaw ?? []);

    // Portfolio
    $portfolio = $coordinator->portfolio ?? [];

    // Client completed booking check
    $hasCompletedBooking = $role === 'client'
        ? \App\Models\Booking::where('client_id', $user->id)
            ->where('coordinator_id', $coordinator->id)
            ->where(fn($q) => $q->where('status', 'completed')
                                ->orWhereDate('event_date', '<', now()))
            ->exists()
        : false;

    // Booking permission (ONLY CLIENT)
    $canBook = $role === 'client';
@endphp

<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- LEFT --}}
    <div class="lg:col-span-2 space-y-8">

        {{-- HEADER --}}
        <div class="bg-white rounded-3xl p-6 flex items-center gap-6 shadow-sm">
            <img src="{{ $coordinatorUser->avatar
                ? asset('storage/'.$coordinatorUser->avatar)
                : 'https://i.pravatar.cc/150?img=12' }}"
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
                            <span class="text-xl {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                        @endfor
                        <span class="text-sm text-gray-500">
                            {{ $averageRating }} ({{ $totalReviews }} reviews)
                        </span>
                    </div>
                @else
                    <p class="mt-2 text-xs text-gray-400 italic">
                        Ratings visible after completion
                    </p>
                @endif

                <p class="text-xs text-gray-400 mt-1">
                    {{ $coordinatorUser->email ?? 'No email provided' }}
                </p>

                <span class="inline-flex items-center gap-2 mt-3 px-4 py-1.5 text-sm rounded-full
                    bg-[#E9F0E6] text-[#3E3F29] font-medium">
                    {{ $coordinatorUser->is_active ?? true ? 'Available for Booking' : 'Currently Busy' }}
                </span>
            </div>
        </div>

        {{-- PORTFOLIO --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-5">Event Designs & Portfolio</h2>

            @if(count($portfolio))
                <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                    @foreach($portfolio as $item)
                        @if(!empty($item['image']))
                            <img src="{{ asset('storage/'.$item['image']) }}"
                                 class="rounded-2xl h-44 w-full object-cover">
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm italic">No portfolio uploaded yet</p>
            @endif
        </div>

        {{-- ABOUT --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-3">About the Coordinator</h2>
            <p class="text-sm text-gray-600">
                {{ $coordinator->bio ?? $coordinatorUser->bio ?? 'No bio provided yet' }}
            </p>
        </div>

        {{-- REVIEWS (FIXED SECTION) --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-5">Client Reviews</h2>

            @php
                $reviews = \App\Models\Reviews::where('coordinator_id', $coordinator->id)
                            ->with('client')
                            ->latest()
                            ->get();
            @endphp

            @forelse($reviews as $review)
                <div class="border-b last:border-none pb-6 mb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            {{-- Client Name --}}
                            <strong class="text-gray-800 text-md">{{ $review->client->name ?? 'Anonymous Client' }}</strong>
                            {{-- Review Date --}}
                            <p class="text-xs text-gray-400">{{ $review->created_at->format('M d, Y') }}</p>
                        </div>
                        
                        {{-- Stars --}}
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-lg {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                    </div>

                    {{-- FEEDBACK TEXT --}}
                    <div class="mt-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <p class="text-sm text-gray-600 italic">
                            "{{ $review->comment ?? $review->feedback ?? 'No written feedback provided.' }}"
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <p class="text-gray-400 text-sm italic">No reviews available for this coordinator yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="space-y-8">

        {{-- SERVICES --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-5">Services Offered</h2>
            @foreach($servicesArray as $service)
                <div class="bg-[#F6F8F5] p-3 rounded-xl mb-2 text-sm text-gray-700">✓ {{ $service }}</div>
            @endforeach
        </div>

        {{-- PRICING --}}
        <div class="bg-[#7E8F78] rounded-3xl p-6 text-white shadow-sm">
            <h2 class="font-semibold mb-3">Pricing</h2>
            <p class="text-4xl font-extrabold">
                ₱{{ number_format($coordinator->rate ?? 0, 2) }}
            </p>
            <p class="text-sm mb-5 opacity-90">per event</p>

            {{-- BOOKING ONLY FOR CLIENT --}}
            @if($canBook)
                <button
                    onclick="document.getElementById('bookingModal-{{ $coordinator->id }}').classList.remove('hidden')"
                    class="w-full mt-6 py-3 rounded-2xl bg-white text-[#3E3F29] font-semibold hover:bg-gray-100 transition">
                    Book Now
                </button>
            @endif
        </div>

    </div>
</div>

{{-- BOOKING MODAL (CLIENT ONLY) --}}
@if($canBook)
    @include('client.partials.booking-modal', ['coordinator' => $coordinator])
@endif

@endsection