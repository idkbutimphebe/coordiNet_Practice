@extends('layouts.client')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
            My Bookings
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            View and track your event bookings.
        </p>
    </div>

    <!-- SEARCH BAR -->
    <div class="flex items-center gap-3">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#3E3F29]/60">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-4.35-4.35m1.85-5.4a7.25 7.25 0 11-14.5 0 7.25 7.25 0 0114.5 0z"/>
                </svg>
            </span>

            <input
                type="text"
                placeholder="Search bookings..."
                class="w-full pl-10 pr-4 py-3 rounded-lg
                       bg-white border border-[#A1BC98]
                       text-sm text-[#3E3F29]
                       placeholder-[#3E3F29]/60
                       focus:outline-none focus:ring-2
                       focus:ring-[#778873]"
            >
        </div>

        <button
            class="px-6 py-3 rounded-lg
                   bg-[#3E3F29] text-white
                   text-sm font-semibold
                   hover:opacity-90 transition">
            Search
        </button>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-3xl shadow-sm overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-[#DCE7D8] text-[#3E3F29]">
                <tr>
                    <th class="py-4 px-6 text-left font-semibold">Event</th>
                    <th class="py-4 px-6 text-left font-semibold">Date</th>
                    <th class="py-4 px-6 text-left font-semibold">Coordinator</th>
                    <th class="py-4 px-6 text-left font-semibold">Status</th>
                    <th class="py-4 px-6 text-center font-semibold">View</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#A1BC98]/40">

                <!-- sample bookings (kept for reference)
                    @php
                        // Example static data — no effect when $bookings is provided by controller
                        $sampleBookings = [
                            ['id' => 1, 'event' => 'Wedding', 'date' => 'Dec 15, 2025', 'coordinator' => 'Juan Dela Cruz', 'status' => 'Completed'],
                            ['id' => 2, 'event' => 'Birthday', 'date' => 'Jan 10, 2026', 'coordinator' => 'Maria Santos', 'status' => 'Pending'],
                            ['id' => 3, 'event' => 'Corporate', 'date' => 'Feb 02, 2026', 'coordinator' => 'Alex Lim', 'status' => 'Cancelled'],
                        ];
                    @endphp -->

@php $bookings = collect($bookings ?? []); @endphp
@foreach($bookings as $booking)
                <tr class="hover:bg-[#F6F8F5] transition">
                    <!-- EVENT NAME -->
                    <td class="py-4 px-6 font-medium text-[#3E3F29]">
                        {{ data_get($booking, 'eventInfo.title') ?? data_get($booking, 'event') ?? 'N/A' }}


                        <!-- SERVICES LIST -->
                        @php $services = collect(data_get($booking, 'services', [])); @endphp
                        @if($services->count())
    <ul class="text-xs text-gray-500 mt-1">
        @foreach($services as $service)
            <li>
                {{ data_get($service, 'service_name') }} -
                ₱{{ number_format((float) data_get($service, 'pivot.price', data_get($service, 'price', 0)), 2) }}
            </li>
        @endforeach
    </ul>
@endif
                    </td>

                    <!-- CREATED DATE -->
                    <td class="py-4 px-6 text-gray-600">
                        @php
                            $created = data_get($booking, 'created_at');
                            try {
                                $createdFormatted = $created ? \Carbon\Carbon::parse($created)->format('M d, Y') : 'N/A';
                            } catch (\Exception $e) {
                                $createdFormatted = 'N/A';
                            }
                        @endphp
                        {{ $createdFormatted }}
                    </td>

                    <!-- COORDINATOR NAME -->
                    <td class="py-4 px-6 text-gray-700">
                        {{ data_get($booking, 'coordinator.name') ?? data_get($booking, 'coordinator') ?? 'N/A' }}
                    </td>

                    <!-- STATUS -->
                    <td class="py-4 px-6">
                        @php $status = data_get($booking, 'status'); @endphp
                        <span class="inline-flex items-center px-4 py-1.5
                            rounded-full text-xs font-semibold
                            @if($status === 'Completed')
                                bg-[#A1BC98] text-[#3E3F29]
                            @elseif($status === 'Cancelled')
                                bg-[#A1BC98]/40 text-[#3E3F29]
                            @else
                                bg-[#A1BC98]/20 text-[#3E3F29]
                            @endif">
                            {{ $status ?? 'N/A' }}
                        </span>
                    </td>

                    <!-- VIEW BUTTON -->
                    <td class="py-4 px-6 text-center">
                        <a href="{{ route('client.bookings.show', data_get($booking, 'id')) }}"
                           class="inline-block px-5 py-1.5 rounded-lg
                                  bg-[#778873] text-white
                                  text-xs font-medium
                                  hover:bg-[#3E3F29] transition">
                            View
                        </a>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="flex justify-center mt-6">
        <nav class="flex items-center gap-2 text-sm">

            <button disabled
                class="px-3 py-1.5 rounded-md bg-[#778873]
                       text-white opacity-40">
                ‹
            </button>

            <button class="px-3 py-1.5 rounded-md
                           bg-[#3E3F29] text-white font-medium">
                1
            </button>

            <button class="px-3 py-1.5 rounded-md bg-[#778873] text-white">
                ›
            </button>

        </nav>
    </div>

</div>
@endsection
