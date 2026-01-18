@extends('layouts.coordinator')

@section('content')

<div class="w-full min-h-screen px-8 py-10 flex justify-center">

    <div class="w-full max-w-4xl">

        <!-- TITLE -->
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-8 bg-[#778873] rounded-full"></div>
            <h1 class="text-2xl font-semibold text-[#3E3F29]">
                Booking Details
            </h1>
        </div>

        <!-- MAIN CARD -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <!-- TOP ACCENT -->
            <div class="h-1 bg-gradient-to-r from-[#A1BC98] to-[#778873]"></div>

            <div class="p-8">

                <!-- CLIENT INFO -->
                <div class="flex items-center gap-4 mb-8">

                    <!-- AVATAR -->
                    <div class="w-14 h-14 rounded-full bg-[#A1BC98]/60
                                flex items-center justify-center
                                text-[#3E3F29] font-bold text-lg">
                        JS
                    </div>

                    <div>
                        <h2 class="text-xl font-semibold text-[#3E3F29]">
                            Jan Tirzuh Santos
                        </h2>
                        <p class="text-sm text-[#778873]">
                            Booking Request
                        </p>
                    </div>

                    <!-- STATUS -->
                    <span class="ml-auto px-4 py-1.5 text-xs rounded-full
                                 bg-[#A1BC98] text-[#3E3F29]
                                 font-semibold shadow-sm">
                        Pending
                    </span>
                </div>

                <!-- DETAILS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="p-5 rounded-xl bg-[#A1BC98]/20">
                        <p class="text-xs uppercase tracking-wide text-[#778873]">
                            Event Requested
                        </p>
                        <p class="mt-1 font-semibold text-[#3E3F29]">
                            Proposal Booking
                        </p>
                    </div>

                    <div class="p-5 rounded-xl bg-[#A1BC98]/20">
                        <p class="text-xs uppercase tracking-wide text-[#778873]">
                            Requested Date
                        </p>
                        <p class="mt-1 font-semibold text-[#3E3F29]">
                            March 13, 2025
                        </p>
                    </div>

                    <div class="p-5 rounded-xl bg-[#A1BC98]/20">
                        <p class="text-xs uppercase tracking-wide text-[#778873]">
                            Coordinator
                        </p>
                        <p class="mt-1 font-semibold text-[#3E3F29]">
                            Not Assigned
                        </p>
                    </div>

                    <div class="p-5 rounded-xl bg-[#A1BC98]/20">
                        <p class="text-xs uppercase tracking-wide text-[#778873]">
                            Request ID
                        </p>
                        <p class="mt-1 font-semibold text-[#3E3F29]">
                            #BK-2025-0313
                        </p>
                    </div>

                </div>

                <!-- ACTION BUTTONS -->
                <div class="mt-10 flex justify-end gap-3">

                    <a href="{{ route('coordinator.bookings') }}"
                       class="px-5 py-2 rounded-lg text-sm
                              bg-[#778873] text-white
                              hover:bg-[#3E3F29]
                              transition">
                        Back
                    </a>

                    <button
                        class="px-5 py-2 rounded-lg text-sm
                               bg-[#3E3F29] text-white
                               hover:opacity-90 transition">
                        Approve
                    </button>

                    <button
                        class="px-5 py-2 rounded-lg text-sm
                               bg-red-600 text-white
                               hover:bg-red-700 transition">
                        Reject
                    </button>

                </div>

            </div>
        </div>

    </div>
</div>

@endsection
