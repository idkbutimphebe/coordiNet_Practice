@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6">

    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
            Bookings
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            Manage and review booking requests.
        </p>
    </div>

    <!-- ================= SEARCH ================= -->
    <form method="GET" action="{{ route('bookings') }}" class="flex items-center gap-3">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#3E3F29]/60">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-4.35-4.35m1.85-5.4a7.25 7.25 0 11-14.5 0 7.25 7.25 0 0114.5 0z"/>
                </svg>
            </span>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
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
            type="submit"
            class="px-6 py-3 rounded-lg
                   bg-[#3E3F29] text-white
                   text-sm font-semibold
                   hover:opacity-90 transition">
            Search
        </button>
    </form>

    <!-- ================= TABLE CARD ================= -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden mt-4">

        <table class="w-full text-sm text-left">
            <thead class="bg-[#A1BC98]/40 text-[#3E3F29]">
                <tr>
                    <th class="py-3 px-5 font-semibold">Name</th>
                    <th class="py-3 px-5 font-semibold">Event Requested</th>
                    <th class="py-3 px-5 font-semibold">Status</th>
                    <th class="py-3 px-5 font-semibold text-center">View</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#778873]/20">
                @forelse($bookings as $booking)
                <tr class="hover:bg-[#A1BC98]/20 transition">
                    <td class="py-3 px-5 font-medium text-[#3E3F29]">
                        {{ $booking->client->name ?? '-' }}
                    </td>

                    <td class="py-3 px-5 text-gray-700">
                        {{ $booking->event->name ?? '-' }}
                    </td>

                    <td class="py-3 px-5">
                        <span class="inline-flex items-center px-4 py-1.5
                            rounded-full text-xs font-medium
                            @if(strtolower($booking->status) === 'approved')
                                bg-[#A1BC98] text-[#3E3F29]
                            @elseif(strtolower($booking->status) === 'cancelled')
                                bg-[#778873]/30 text-[#3E3F29]
                            @else
                                bg-[#E9F0E6] text-[#3E3F29]
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>

                    <td class="py-3 px-5 text-center">
                        <a href="{{ route('bookings.show', $booking->id) }}"
                           class="inline-block px-4 py-1.5 text-xs rounded-lg
                                  bg-[#778873] text-white
                                  hover:bg-[#3E3F29] transition">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 px-5 text-center text-gray-500">
                        No bookings found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ================= PAGINATION ================= -->
    <div class="mt-6 py-4">
        <div class="flex justify-center">
            {{ $bookings->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection
