@extends('layouts.client')

@section('content')

@php
    // Get the User model associated with this coordinator profile
    $coordinatorUser = $coordinator->user; 

    // Average rating and total reviews
    $averageRating = \App\Models\Reviews::where('coordinator_id', $coordinator->id)->avg('rating') ?? 0;
    $averageRating = number_format($averageRating, 1);

    $totalReviews = \App\Models\Reviews::where('coordinator_id', $coordinator->id)->count();

    // Get services from coordinator
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

    {{-- LEFT COLUMN --}}
    <div class="lg:col-span-2 space-y-8">

        {{-- PROFILE HEADER --}}
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
                            <span class="text-xl {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
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

        {{-- PORTFOLIO SECTION --}}
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

        {{-- ABOUT SECTION --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-3">
                About the Coordinator
            </h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                {{ $coordinator->bio ?? $coordinatorUser->bio ?? 'No bio provided yet ✨' }}
            </p>
        </div>

        {{-- REVIEWS SECTION (Feedback Shows Here) --}}
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
                                    <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                @endfor
                            </div>
                        </div>

<p class="text-sm text-gray-600 mt-2">
    {{ $review->feedback ?? 'No comment provided' }}  </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $review->created_at->format('M d, Y') }}
                        </p>
                    </div>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center py-6 text-gray-400">
                    <span class="text-2xl mb-2">💬</span>
                    <p class="text-sm italic">No reviews yet. Be the first to book!</p>
                </div>
            @endif
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="space-y-8">

        {{-- SERVICES --}}
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

        {{-- AVAILABILITY CALENDAR --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-[#3E3F29] mb-4">Availability</h2>
            <div class="grid grid-cols-7 gap-2 text-center text-sm">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                    <div class="text-xs text-gray-400">{{ $day }}</div>
                @endforeach
                @for($i = 1; $i <= 31; $i++)
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-[#DCE7D8]">
                        {{ $i }}
                    </div>
                @endfor
            </div>
        </div>

        {{-- PRICING & BACK BUTTON --}}
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

            {{-- FIXED: Functional Back Button --}}
            {{-- onclick="history.back()" makes it act exactly like the browser back button --}}
            <button onclick="history.back()" 
               class="block w-full mt-6 py-3 rounded-2xl 
                      bg-white text-[#3E3F29] font-semibold 
                      hover:opacity-90 transition text-center cursor-pointer shadow-lg">
                Back
            </button>
        </div>

    </div>
</div>

@endsection