@extends('layouts.client')

@section('content')

@php
    $ratings = [
        ['name' => 'Jan Tirzuh Santos', 'stars' => 5, 'comment' => 'Very professional and well organized.'],
        ['name' => 'Maria Lopez', 'stars' => 4, 'comment' => 'Smooth coordination and great communication.'],
        ['name' => 'Carlos Reyes', 'stars' => 5, 'comment' => 'Highly recommended! Stress-free event.'],
    ];

    $averageRating = collect($ratings)->avg('stars');
    $totalReviews = count($ratings);
@endphp

<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- ================= LEFT CONTENT ================= -->
    <div class="lg:col-span-2 space-y-8">

        <!-- PROFILE CARD -->
        <div class="bg-white rounded-3xl p-6 flex items-center gap-6 shadow-sm">
            <img src="https://i.pravatar.cc/150?img=12"
                 class="w-24 h-24 rounded-full border-4 border-[#A1BC98] object-cover">

            <div class="flex-1">
                <h1 class="text-2xl font-extrabold text-[#3E3F29]">
                    Juan Dela Cruz
                </h1>

                <p class="text-sm text-gray-600">
                    Birthday & Wedding Event Coordinator
                </p>

                <!-- RATING -->
                <div class="flex items-center gap-2 mt-2">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="text-xl {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}">
                            ★
                        </span>
                    @endfor
                    <span class="text-sm text-gray-500">
                        {{ number_format($averageRating, 1) }} ({{ $totalReviews }} reviews)
                    </span>
                </div>

                <span class="inline-flex items-center gap-2 mt-3 px-4 py-1.5
                             text-sm rounded-full bg-[#E9F0E6]
                             text-[#3E3F29] font-medium">
                    Available for Booking
                </span>
            </div>
        </div>

        <!-- ================= PORTFOLIO ================= -->
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-5">
                Event Designs & Portfolio
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                @foreach([
                    'https://images.unsplash.com/photo-1522156373667-4c7234bbd804',
                    'https://images.unsplash.com/photo-1519741497674-611481863552',
                    'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e',
                    'https://images.unsplash.com/photo-1519225421980-715cb0215aed',
                    'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee',
                    'https://images.unsplash.com/photo-1503424886307-b090341d25d1'
                ] as $image)
                    <img src="{{ $image }}"
                         class="rounded-2xl h-44 w-full object-cover hover:scale-105 transition">
                @endforeach
            </div>
        </div>

        <!-- ABOUT -->
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-3">
                About the Coordinator
            </h2>

            <p class="text-sm text-gray-600 leading-relaxed">
                Professional event coordinator with over 3 years of experience
                handling weddings, birthdays, and corporate events. Known for
                attention to detail, strong vendor coordination, and smooth
                event execution from planning to post-event wrap-up.
            </p>
        </div>

        <!-- ================= CLIENT REVIEWS ================= -->
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-5">
                Client Reviews
            </h2>

            @foreach($ratings as $review)
                <div class="border-b last:border-none pb-4 mb-4 last:mb-0">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-[#3E3F29]">
                            {{ $review['name'] }}
                        </p>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $review['stars'] ? 'text-yellow-400' : 'text-gray-300' }}">
                                    ★
                                </span>
                            @endfor
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mt-2">
                        {{ $review['comment'] }}
                    </p>
                </div>
            @endforeach
        </div>

    </div>

    <!-- ================= RIGHT SIDEBAR ================= -->
    <div class="space-y-8">

        <!-- SERVICES -->
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-lg text-[#3E3F29] mb-5">
                Services Offered
            </h2>

            <div class="space-y-3 text-sm">
                @foreach([
                    'Full Event Planning',
                    'Vendor Coordination',
                    'Program & Timeline Management',
                    'On-site Supervision',
                    'Budget Planning',
                    'Post-event Coordination'
                ] as $service)
                    <div class="flex items-center gap-3 bg-[#F6F8F5] p-3 rounded-xl">
                        <span class="text-green-600 font-bold">✓</span>
                        <span class="font-medium text-[#3E3F29]">{{ $service }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- AVAILABILITY -->
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-semibold text-[#3E3F29] mb-4">
                Availability
            </h2>

            <div class="grid grid-cols-7 gap-2 text-center text-sm">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                    <div class="text-xs text-gray-400">{{ $day }}</div>
                @endforeach

                @for($i = 1; $i <= 31; $i++)
                    @php $booked = in_array($i, [5, 12, 18, 25]); @endphp
                    <div class="w-10 h-10 flex items-center justify-center rounded-full
                        {{ $booked ? 'bg-red-200 text-red-600' : 'bg-[#DCE7D8]' }}">
                        {{ $i }}
                    </div>
                @endfor
            </div>
        </div>

        <!-- PRICING -->
        <div class="bg-[#7E8F78] rounded-3xl p-6 text-white shadow-sm">
            <h2 class="font-semibold mb-3">Pricing</h2>

            <p class="text-4xl font-extrabold">₱5,000</p>
            <p class="text-sm mb-5 opacity-90">per event</p>

            <ul class="space-y-2 text-sm">
                <li>✓ Full coordination</li>
                <li>✓ On-site supervision</li>
                <li>✓ Client support</li>
            </ul>

            <button class="block w-full mt-6 py-3 rounded-2xl
                           bg-white text-[#3E3F29] font-semibold
                           hover:opacity-90 transition">
                Book Now
            </button>
        </div>

    </div>
</div>

@endsection
