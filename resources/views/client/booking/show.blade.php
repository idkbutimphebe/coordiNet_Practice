@extends('layouts.client')

@section('content')

<div class="flex justify-center mt-12">
    <div class="w-full max-w-4xl space-y-6">

        <!-- Header -->
        <div class="flex items-center gap-3">
            <div class="w-1 h-8 bg-[#778873] rounded-full"></div>
            <h1 class="text-2xl font-semibold text-[#3E3F29]">
                Booking Details
            </h1>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="p-3 bg-green-200 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 bg-red-200 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Booking Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-[#A1BC98] to-[#778873]"></div>

            <div class="p-8">

                <!-- Client Info -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-full bg-[#A1BC98]/60 flex items-center justify-center
                                text-[#3E3F29] font-bold text-lg">
                        {{ strtoupper(substr($booking->client->name ?? 'NA', 0, 2)) }}
                    </div>

                    <div>
                        <h2 class="text-xl font-semibold text-[#3E3F29]">
                            {{ $booking->client->name ?? 'No Name' }}
                        </h2>
                        <p class="text-sm text-[#778873]">
                            Your Booking Request
                        </p>
                    </div>

                    <span class="ml-auto px-4 py-1.5 text-xs rounded-full font-semibold bg-[#A1BC98] text-[#3E3F29]">
                        {{ ucfirst($booking->status ?? 'Pending') }}
                    </span>
                </div>

                <!-- Booking Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-5 rounded-xl bg-[#A1BC98]/20">
                        <p class="text-xs uppercase text-[#778873]">Event</p>
                        <p class="font-semibold text-[#3E3F29]">{{ $booking->event_name ?? 'N/A' }}</p>
                    </div>

                    <div class="p-5 rounded-xl bg-[#A1BC98]/20">
                        <p class="text-xs uppercase text-[#778873]">Event Date</p>
                        <p class="font-semibold text-[#3E3F29]">
                            {{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('F d, Y') : 'No Date' }}
                        </p>
                    </div>

                    <div class="p-5 rounded-xl bg-[#A1BC98]/20">
                        <p class="text-xs uppercase text-[#778873]">Coordinator</p>
                        <p class="font-semibold text-[#3E3F29]">{{ $booking->coordinator->name ?? 'No Coordinator' }}</p>
                    </div>

                    <div class="p-5 rounded-xl bg-[#A1BC98]/20">
                        <p class="text-xs uppercase text-[#778873]">Booking ID</p>
                        <p class="font-semibold text-[#3E3F29]">{{ $booking->id }}</p>
                    </div>
                </div>

                <!-- ⭐ Rating Form -->
                <div class="mt-10">
                    <p class="text-sm font-semibold text-[#3E3F29] mb-3">
                        Rate Your Coordinator
                    </p>

                    <form method="POST" action="{{ route('client.ratings.store') }}">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        <div class="flex flex-row-reverse justify-end gap-1 mb-4">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio"
                                       id="star{{ $i }}"
                                       name="rating"
                                       value="{{ $i }}"
                                       class="peer hidden">
                                <label for="star{{ $i }}"
                                       class="cursor-pointer text-3xl text-gray-300
                                              hover:text-yellow-400
                                              peer-checked:text-yellow-400
                                              transition">
                                    ★
                                </label>
                            @endfor
                        </div>

                        <textarea
                            name="feedback"
                            rows="3"
                            placeholder="Optional feedback about the coordinator’s performance"
                            class="w-full rounded-xl p-3 bg-[#A1BC98]/20
                                   text-sm text-[#3E3F29]
                                   placeholder-[#778873]
                                   focus:outline-none"></textarea>

                        <button type="submit"
                                class="mt-4 px-5 py-2 rounded-lg text-sm
                                       bg-[#778873] text-white
                                       hover:bg-[#3E3F29]
                                       transition">
                            Submit Rating
                        </button>
                    </form>
                </div>

                <!-- Back Button -->
                <div class="mt-10 flex justify-end">
                    <a href="{{ route('client.bookings.index') }}"
                       class="px-5 py-2 rounded-lg text-sm
                              bg-[#E9F0E6] text-[#3E3F29]
                              border border-[#A1BC98]/40
                              hover:bg-[#A1BC98]/40 transition">
                        Back
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
