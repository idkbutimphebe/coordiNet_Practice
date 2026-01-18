@extends('layouts.dashboard')

@section('content')

@php
    $ratings = [
        ['name' => 'Jan Tirzuh Santos', 'stars' => 5, 'comment' => 'Very professional and well organized.'],
        ['name' => 'Maria Lopez', 'stars' => 4, 'comment' => 'Smooth coordination and great communication.'],
        ['name' => 'Carlos Reyes', 'stars' => 5, 'comment' => 'Highly recommended! Stress-free event.'],
    ];

    $averageRating = collect($ratings)->avg('stars');
@endphp

<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- ================= LEFT CONTENT ================= -->
    <div class="lg:col-span-2 space-y-6">

        <!-- PROFILE -->
        <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-5">
            <img src="{{ asset('image/premium_photo-1661374927471-24a90ebd5737.jpg') }}"
                 class="w-20 h-20 rounded-full object-cover border-4 border-[#A1BC98]">

            <div class="flex-1">
                <h1 class="text-xl font-bold text-[#3E3F29]">
                    Juan Dela Cruz
                </h1>

                <p class="text-sm text-gray-500">
                    Professional Event Coordinator
                </p>

                <div class="flex items-center gap-1 mt-2">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                    @endfor
                    <span class="text-xs text-gray-500 ml-2">
                        {{ number_format($averageRating,1) }} Rating
                    </span>
                </div>

                <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full
                             bg-[#E9F0E6] text-[#3E3F29] font-medium">
                    Active Coordinator
                </span>
            </div>
        </div>

        <!-- ABOUT -->
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h2 class="font-semibold text-[#3E3F29] mb-2">
                About
            </h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                Experienced coordinator specializing in weddings, birthdays,
                and corporate events. Known for smooth execution and excellent
                client satisfaction.
            </p>
        </div>

        <!-- SERVICES -->
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h2 class="font-semibold text-[#3E3F29] mb-4">
                Services Offered
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                @foreach([
                    'Full Event Planning',
                    'Vendor Coordination',
                    'Timeline Management',
                    'On-site Supervision',
                    'Budget Planning',
                    'Post-event Support'
                ] as $service)
                    <div class="flex items-center gap-3 bg-[#F6F8F5] px-4 py-3 rounded-xl">
                        <span class="text-green-600 font-bold">✔</span>
                        <span class="font-medium text-[#3E3F29]">{{ $service }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- REVIEWS -->
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h2 class="font-semibold text-[#3E3F29] mb-4">
                Client Feedback
            </h2>

            @foreach($ratings as $review)
                <div class="border-b last:border-none pb-4 mb-4 last:mb-0">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-[#3E3F29]">
                            {{ $review['name'] }}
                        </p>
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $review['stars'] ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mt-1">
                        {{ $review['comment'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ================= RIGHT SIDEBAR ================= -->
    <div class="space-y-6">

        <!-- AVAILABILITY -->
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h3 class="font-semibold text-[#3E3F29] mb-4">
                Availability Overview
            </h3>

            <div class="grid grid-cols-7 gap-2 text-center text-xs">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                    <div class="text-gray-400 font-medium">{{ $day }}</div>
                @endforeach

                @for($i = 1; $i <= 30; $i++)
                    <div class="w-9 h-9 flex items-center justify-center rounded-full
                        {{ in_array($i,[6,12,18,24]) ? 'bg-red-100 text-red-500' : 'bg-[#A1BC98]/40 text-[#3E3F29]' }}">
                        {{ $i }}
                    </div>
                @endfor
            </div>

            <div class="flex gap-4 mt-4 text-xs text-gray-600">
                <span class="flex items-center gap-1">
                    <span class="w-3 h-3 bg-[#A1BC98] rounded-full"></span> Available
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-3 h-3 bg-red-300 rounded-full"></span> Booked
                </span>
            </div>
        </div>

        <!-- PRICING -->
        <div class="bg-[#778873] text-white rounded-2xl p-6 shadow-sm">
            <h3 class="font-semibold mb-2">Base Rate</h3>
            <p class="text-3xl font-bold">₱5,000</p>
            <p class="text-xs opacity-90 mb-4">per event</p>

            <ul class="text-sm space-y-2">
                <li>✔ Full coordination</li>
                <li>✔ On-site supervision</li>
                <li>✔ Client assistance</li>
            </ul>
        </div>

    </div>
</div>

@endsection
