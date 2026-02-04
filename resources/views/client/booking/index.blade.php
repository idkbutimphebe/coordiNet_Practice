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

            <form method="GET" action="{{ route('client.bookings.index') }}">
                <input
                    type="text"
                    name="search"
                    placeholder="Search bookings..."
                    class="w-full pl-10 pr-4 py-3 rounded-lg
                           bg-white border border-[#A1BC98]
                           text-sm text-[#3E3F29]
                           placeholder-[#3E3F29]/60
                           focus:outline-none focus:ring-2
                           focus:ring-[#778873]"
                    value="{{ request('search') }}"
                >
            </form>
        </div>

        <button
            onclick="document.querySelector('form').submit();"
            class="px-6 py-3 rounded-lg
                   bg-[#3E3F29] text-white
                   text-sm font-semibold
                   hover:opacity-90 transition">
            Search
        </button>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-3xl shadow-sm overflow-x-auto">

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
                @forelse($bookings as $booking)
                <tr class="hover:bg-[#F6F8F5] transition">
                    <td class="py-4 px-6 font-medium text-[#3E3F29]">
                        {{ $booking->event->name ?? 'N/A' }}
                    </td>

                    <td class="py-4 px-6 text-gray-600">
                        {{ $booking->event_date->format('M d, Y') ?? 'N/A' }}
                    </td>

                    <td class="py-4 px-6 text-gray-700">
                        {{ $booking->coordinator->name ?? 'N/A' }}
                    </td>

                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-4 py-1.5
                            rounded-full text-xs font-semibold
                            @if($booking->status === 'Completed')
                                bg-[#A1BC98] text-[#3E3F29]
                            @elseif($booking->status === 'Cancelled')
                                bg-[#A1BC98]/40 text-[#3E3F29]
                            @else
                                bg-[#A1BC98]/20 text-[#3E3F29]
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>

                    <td class="py-4 px-6 text-center">
                        <a href="{{ route('client.bookings.show', $booking->id) }}"
                           class="inline-block px-5 py-1.5 rounded-lg
                                  bg-[#778873] text-white
                                  text-xs font-medium
                                  hover:bg-[#3E3F29] transition">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-[#3E3F29]">
                        No bookings found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="flex justify-center mt-6">
        {{ $bookings->links() }}
    </div>

</div>
@endsection
