@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between no-print">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
                Bookings List
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Complete list of all bookings
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 text-sm rounded-lg
                      border border-[#A1BC98]
                      text-[#3E3F29]
                      hover:bg-[#E3EAD7] transition">
                ← Back
            </a>

            <button onclick="window.print()" 
                    class="px-4 py-2 text-sm rounded-lg
                           bg-[#3E3F29] text-white
                           hover:bg-[#2c2d1f] transition">
                🖨️ Print
            </button>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="bg-white rounded-xl shadow-sm p-5 no-print">
        <form method="GET" action="{{ route('reports.bookings') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <!-- Search Bar -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-[#3E3F29] mb-2">Search</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by client, event, or coordinator..."
                       class="w-full px-4 py-2 rounded-lg border border-[#A1BC98] 
                              focus:outline-none focus:ring-2 focus:ring-[#A1BC98]">
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-[#3E3F29] mb-2">Date From</label>
                <input type="date" 
                       name="date_from" 
                       value="{{ request('date_from') }}"
                       class="w-full px-4 py-2 rounded-lg border border-[#A1BC98] 
                              focus:outline-none focus:ring-2 focus:ring-[#A1BC98]">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-medium text-[#3E3F29] mb-2">Date To</label>
                <input type="date" 
                       name="date_to" 
                       value="{{ request('date_to') }}"
                       class="w-full px-4 py-2 rounded-lg border border-[#A1BC98] 
                              focus:outline-none focus:ring-2 focus:ring-[#A1BC98]">
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-4 flex gap-3">
                <button type="submit" 
                        class="px-6 py-2 rounded-lg bg-[#A1BC98] text-[#3E3F29] 
                               font-medium hover:bg-[#8aa880] transition">
                    Apply Filters
                </button>
                <a href="{{ route('reports.bookings') }}" 
                   class="px-6 py-2 rounded-lg border border-[#A1BC98] text-[#3E3F29] 
                          hover:bg-[#E3EAD7] transition">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- ================= TABLE CARD ================= -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden print:shadow-none">

        <table class="w-full text-sm text-left">
    <thead class="bg-[#A1BC98]/40 text-[#3E3F29]">
        <tr>
            <th class="py-3 px-5 font-semibold">#</th>
            <th class="py-3 px-5 font-semibold">Client</th>
            <th class="py-3 px-5 font-semibold">Event</th>
            <th class="py-3 px-5 font-semibold">Coordinator</th>
            <th class="py-3 px-5 font-semibold">Schedule</th>
            <th class="py-3 px-5 font-semibold">Status</th>
        </tr>
    </thead>

    <tbody class="divide-y divide-[#778873]/20">
        @forelse($bookings as $booking)
            <tr class="hover:bg-[#A1BC98]/20 transition print:hover:bg-transparent">
                <td class="py-3 px-5 font-bold text-[#3E3F29]">{{ $booking->id }}</td>
                <td class="py-3 px-5 text-[#3E3F29]">{{ $booking->client->name ?? 'N/A' }}</td>
                <td class="py-3 px-5 text-gray-700">{{ $booking->event->name ?? $booking->event_name ?? 'N/A' }}</td>
                <td class="py-3 px-5 text-gray-700">{{ $booking->event->coordinator->coordinator_name ?? 'N/A' }}</td>
                <td class="py-3 px-5 text-gray-700">
                    {{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}
                    <br>
                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} -
                    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                </td>
                <td class="py-3 px-5">
                    <span class="inline-block px-3 py-1 text-xs rounded-full
                        {{ $booking->status === 'confirmed'
                            ? 'bg-[#A1BC98] text-[#3E3F29]'
                            : ($booking->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="py-4 px-5 text-center text-gray-500">
                    No bookings found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>


    </div>

    <!-- PAGINATION -->
    <div class="mt-6 py-4 no-print">
        <div class="flex justify-center">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    </div>

</div>

<!-- ================= PRINT RULES ================= -->
<style>
@media print {

    .no-print {
        display: none !important;
    }

    body * {
        background: white !important;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid #A1BC98 !important;
        padding: 12px 20px !important;
    }

    th {
        background: #A1BC98 !important;
        color: #3E3F29 !important;
        font-weight: 600 !important;
    }
}
</style>
@endsection