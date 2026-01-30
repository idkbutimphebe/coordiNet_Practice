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
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- RATING CARD -->
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
                    Wedding Event
                </h3>
                <p class="text-sm text-[#778873]">
                    Coordinator: Juan Dela Cruz
                </p>
            </div>

            <!-- RATING DISPLAY -->
            <div class="flex items-center gap-4 mb-4">
                <div class="text-4xl font-extrabold text-[#D4A72C]">
                    5.0
                </div>

                <div>
                    <div class="flex gap-1 text-[#D4A72C]">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.377 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.378-2.455a1 1 0 00-1.175 0l-3.378 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.287-3.966z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        Excellent
                    </p>
                </div>
            </div>

            <!-- COMMENT -->
            <p class="text-sm text-gray-600 leading-relaxed italic border-l-4
                      border-[#A1BC98] pl-4">
                “Excellent service and very organized. Everything went smoothly
                and communication was clear from start to finish.”
            </p>
        </div>

        <!-- SECOND CARD -->
        <div class="relative bg-gradient-to-br from-[#F7F8F3] to-white
                    rounded-3xl p-6 shadow-lg
                    hover:shadow-2xl transition-all duration-300
                    hover:-translate-y-1">

            <span class="absolute -top-3 right-6 px-4 py-1 text-xs rounded-full
                         bg-[#3E3F29] text-white font-semibold shadow">
                Completed
            </span>

            <div class="mb-4">
                <h3 class="text-xl font-bold text-[#3E3F29]">
                    Birthday Event
                </h3>
                <p class="text-sm text-[#778873]">
                    Coordinator: Maria Santos
                </p>
            </div>

            <div class="flex items-center gap-4 mb-4">
                <div class="text-4xl font-extrabold text-[#D4A72C]">
                    4.0
                </div>

                <div>
                    <div class="flex gap-1 text-[#D4A72C]">
                        @for($i = 0; $i < 4; $i++)
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.377 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.378-2.455a1 1 0 00-1.175 0l-3.378 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.287-3.966z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        Very Good
                    </p>
                </div>
            </div>

            <p class="text-sm text-gray-600 leading-relaxed italic border-l-4
                      border-[#A1BC98] pl-4">
                “Smooth coordination and very friendly. Would definitely
                recommend for future events.”
            </p>
        </div>

    </div>

</div>
@endsection
