@extends('layouts.coordinator')

@section('content')

<div class="space-y-12">

    <!-- HEADER -->
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

    <!-- HERO RATING CARD -->
    <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-br from-[#778873] to-[#5E6F5A] shadow-2xl">

        <!-- Glow -->
        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>

        <div class="relative p-10 grid grid-cols-1 md:grid-cols-2 gap-10 items-center text-white">

            <!-- LEFT -->
            <div>
                <p class="uppercase tracking-widest text-xs opacity-80 mb-3">
                    Overall Rating
                </p>
                <div class="flex items-end gap-4">
                    <span class="text-7xl font-black leading-none">
                        4.7
                    </span>
                    <div class="pb-2">
                        <div class="text-2xl tracking-wide">
                            ★★★★☆
                        </div>
                        <p class="text-xs opacity-80 mt-1">
                            Average client satisfaction
                        </p>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
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

    <!-- FEEDBACK LIST -->
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-[#2F3024]">
                Client Reviews
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- REVIEW CARD -->
            <div class="rounded-2xl bg-white shadow-lg hover:shadow-xl transition p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="font-semibold text-[#2F3024]">
                            Maria Santos
                        </p>
                        <p class="text-xs text-gray-500">
                            Wedding Event
                        </p>
                    </div>
                    <span class="text-xs text-gray-400">
                        Dec 15, 2025
                    </span>
                </div>

                <div class="text-yellow-400 text-sm mb-4">
                    ★★★★★
                </div>

                <p class="text-sm text-gray-600 leading-relaxed">
                    Everything was perfectly organized. The team exceeded our expectations from start to finish.
                </p>
            </div>

            <div class="rounded-2xl bg-white shadow-lg hover:shadow-xl transition p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="font-semibold text-[#2F3024]">
                            John Reyes
                        </p>
                        <p class="text-xs text-gray-500">
                            Birthday Event
                        </p>
                    </div>
                    <span class="text-xs text-gray-400">
                        Dec 12, 2025
                    </span>
                </div>

                <div class="text-yellow-400 text-sm mb-4">
                    ★★★★☆
                </div>

                <p class="text-sm text-gray-600 leading-relaxed">
                    Smooth coordination and very accommodating staff. Would definitely book again.
                </p>
            </div>

            <div class="rounded-2xl bg-white shadow-lg hover:shadow-xl transition p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="font-semibold text-[#2F3024]">
                            Anna Cruz
                        </p>
                        <p class="text-xs text-gray-500">
                            Corporate Meeting
                        </p>
                    </div>
                    <span class="text-xs text-gray-400">
                        Dec 5, 2025
                    </span>
                </div>

                <div class="text-yellow-400 text-sm mb-4">
                    ★★★★☆
                </div>

                <p class="text-sm text-gray-600 leading-relaxed">
                    Professional handling and excellent time management throughout the event.
                </p>
            </div>

        </div>

    </div>

</div>

@endsection
