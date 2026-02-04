@extends('layouts.client')

@section('content')
<div class="space-y-10">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <div>
           <h1 class="text-3xl font-extrabold text-[#3E3F29]">
            My Ratings
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Reviews you’ve shared with your coordinators
            </p>
        </div>
    </div>

    <!-- RATINGS GRID -->
    @if($reviews->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($reviews as $review)
            <div class="relative bg-gradient-to-br from-[#F7F8F3] to-white
                        rounded-3xl p-6 shadow-lg
                        hover:shadow-2xl transition-all duration-300
                        hover:-translate-y-1">

                <!-- FLOATING BADGE -->
                <span class="absolute -top-3 right-6 px-4 py-1 text-xs rounded-full
                             bg-[#3E3F29] text-white font-semibold shadow">
                    Completed
                </span>

                <!-- TITLE -->
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-[#3E3F29]">
                        {{ $review->booking->event_name ?? 'Event' }}
                    </h3>
                    <p class="text-sm text-[#778873]">
                        Coordinator: {{ $review->coordinator->name ?? 'N/A' }}
                    </p>
                </div>

                <!-- RATING DISPLAY -->
                <div class="flex items-center gap-4 mb-4">
                    <div class="text-4xl font-extrabold text-[#D4A72C]">
                        {{ number_format($review->rating, 1) }}
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 mt-1">
                            @if($review->rating >= 4.5)
                                Excellent
                            @elseif($review->rating >= 3.5)
                                Very Good
                            @elseif($review->rating >= 2.5)
                                Good
                            @else
                                Needs Improvement
                            @endif
                        </p>
                    </div>
                </div>

                <!-- COMMENT -->
                <p class="text-sm text-gray-600 leading-relaxed italic border-l-4
                          border-[#A1BC98] pl-4">
                    "{{ $review->feedback }}"
                </p>
            </div>
            @endforeach
        </div>
    @else
        <!-- EMPTY STATE -->
        <div class="flex flex-col items-center justify-center mt-16 gap-4 text-gray-400">
            <p class="text-lg font-semibold">You haven’t submitted any ratings yet.</p>
            <p class="text-sm text-gray-500 text-center">Once you rate your coordinators, they will appear here.</p>
        </div>
    @endif

</div>
@endsection
