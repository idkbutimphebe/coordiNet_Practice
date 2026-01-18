@extends('layouts.client')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
            My Ratings 
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            View and track your event bookings.
        </p>
    </div>
    
    <p class="text-sm text-[#778873]">
        Reviews you’ve given to your coordinators.
    </p>

    <!-- RATINGS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- RATING CARD -->
        <div class="bg-white rounded-2xl shadow-md p-6
                    hover:shadow-lg transition">

            <!-- TOP -->
            <div class="flex items-center justify-between mb-4">

                <div>
                    <h3 class="font-semibold text-lg text-[#3E3F29]">
                        Wedding Event
                    </h3>
                    <p class="text-sm text-[#778873]">
                        Coordinator: Juan Dela Cruz
                    </p>
                </div>

                <span class="px-3 py-1 text-xs rounded-full
                             bg-[#A1BC98]/40 text-[#3E3F29]
                             font-semibold">
                    Completed
                </span>
            </div>

            <!-- STARS -->
            <div class="flex items-center gap-1 mb-3 text-[#D4A72C]">
                @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.377 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.378-2.455a1 1 0 00-1.175 0l-3.378 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.287-3.966z"/>
                    </svg>
                @endfor
            </div>

            <!-- COMMENT -->
            <p class="text-sm text-gray-600 leading-relaxed">
                Excellent service and very organized. Everything went smoothly
                and communication was clear from start to finish.
            </p>
        </div>

        <!-- RATING CARD -->
        <div class="bg-white rounded-2xl shadow-md p-6
                    hover:shadow-lg transition">

            <!-- TOP -->
            <div class="flex items-center justify-between mb-4">

                <div>
                    <h3 class="font-semibold text-lg text-[#3E3F29]">
                        Birthday Event
                    </h3>
                    <p class="text-sm text-[#778873]">
                        Coordinator: Maria Santos
                    </p>
                </div>

                <span class="px-3 py-1 text-xs rounded-full
                             bg-[#A1BC98]/40 text-[#3E3F29]
                             font-semibold">
                    Completed
                </span>
            </div>

            <!-- STARS -->
            <div class="flex items-center gap-1 mb-3 text-[#D4A72C]">
                @for($i = 0; $i < 4; $i++)
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.377 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.378-2.455a1 1 0 00-1.175 0l-3.378 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.287-3.966z"/>
                    </svg>
                @endfor
            </div>

            <!-- COMMENT -->
            <p class="text-sm text-gray-600 leading-relaxed">
                Smooth coordination and very friendly. Would definitely
                recommend for future events.
            </p>
        </div>

    </div>

</div>

@endsection