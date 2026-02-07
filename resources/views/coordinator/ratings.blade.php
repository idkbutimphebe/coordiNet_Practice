@extends('layouts.coordinator')

@section('content')

<div class="space-y-12">

    <div class="flex items-end justify-between">
        <div>
            <h1 class="text-4xl font-extrabold text-[#2F3024] tracking-tight">
                Ratings & Feedback
            </h1>
            <p class="mt-1 text-sm text-[#2F3024]/60">
                Real feedback from real clients
            </p>
        </div>
    </div>

    <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-br from-[#778873] to-[#5E6F5A] shadow-2xl">

        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>

        <div class="relative p-10 grid grid-cols-1 md:grid-cols-2 gap-10 items-center text-white">

            <div>
                <p class="uppercase tracking-widest text-xs opacity-80 mb-3">
                    Overall Rating
                </p>
                <div class="flex items-end gap-4">
                    <span class="text-7xl font-black leading-none">
                        {{ $formattedAvg }}
                    </span>
                    <div class="pb-2">
                        <div class="text-2xl tracking-wide text-yellow-400">
                            @php $roundedAvg = round($formattedAvg); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $roundedAvg)
                                    ★
                                @else
                                    <span class="text-white/30">★</span>
                                @endif
                            @endfor
                        </div>
                        <p class="text-xs opacity-80 mt-1">
                            Average client satisfaction ({{ $totalReviews }} reviews)
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-4 text-sm opacity-90">
                <p>
                    This rating reflects the overall experience of clients who have booked and completed events through the system.
                </p>
                <p>
                    Feedback is collected after each completed booking to ensure transparency and service improvement.
                </p>
            </div>

        </div>
    </div>

<div class="space-y-6">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-[#2F3024]">
                Client Reviews
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- 1. FIX: Loop through $ratings as $booking (matches your controller) --}}
            @forelse($ratings as $booking)
                <div class="rounded-2xl bg-white shadow-lg hover:shadow-xl transition p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="font-semibold text-[#2F3024]">
                                {{-- 2. FIX: Get Client Name from the Booking relationship --}}
                                {{ $booking->client->name ?? 'Client' }}
                            </p>
                            
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{-- 3. FIX: Get Event Name --}}
                                {{ $booking->event->event_name ?? 'Event' }}
                            </p>
                        </div>
                        <span class="text-xs text-gray-400">
                            {{-- 4. FIX: Use the date from the connected review --}}
                            {{ optional($booking->reviews)->created_at ? $booking->reviews->created_at->format('M d, Y') : '-' }}
                        </span>
                    </div>

                    {{-- 5. FIX: Get Rating from the 'reviews' relationship --}}
                    <div class="text-yellow-400 text-sm mb-4">
                        @php $rating = $booking->reviews->rating ?? 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $rating)
                                ★
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{-- 6. FIX: Get Feedback from the 'reviews' relationship --}}
                        {{ $booking->reviews->feedback ?? 'No written feedback provided.' }}
                    </p>
                </div>
            @empty
                <div class="col-span-1 lg:col-span-3 flex flex-col items-center justify-center py-16 px-6 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 text-center">
                    <div class="bg-white p-4 rounded-full shadow-sm mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No reviews yet</h3>
                    <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">
                        Once you complete your first booking, client feedback and ratings will appear here.
                    </p>
                </div>
            @endforelse

        </div>

    </div>

</div>

@endsection