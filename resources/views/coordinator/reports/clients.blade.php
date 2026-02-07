@extends('layouts.coordinator')

@section('content')

<div class="p-6 space-y-8">

    <div class="flex items-center justify-between no-print">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
                Client Report
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                List of clients and their assigned event details
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('coordinator.dashboard') }}"
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
        <form method="GET" action="{{ route('coordinator.reports.clients') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
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
                <a href="{{ route('coordinator.reports.clients') }}" 
                   class="px-6 py-2 rounded-lg border border-[#A1BC98] text-[#3E3F29] 
                          hover:bg-[#E3EAD7] transition">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <div class="print-area bg-white rounded-2xl shadow-sm overflow-hidden">

        <!-- Print Header (only visible when printing) -->
        <div class="print-header">
            <h2 class="text-2xl font-bold text-[#3E3F29] mb-2">Client Report</h2>
            <p class="text-sm text-gray-600 mb-4">Generated on {{ now()->format('F d, Y') }}</p>
        </div>

        <table class="w-full text-sm text-left">
            <thead class="bg-[#A1BC98]/40 text-[#3E3F29]">
                <tr>
                    <th class="py-3 px-5 font-semibold">Client Name</th>
                    <th class="py-3 px-5 font-semibold">Event</th>
                    <th class="py-3 px-5 font-semibold">Coordinator</th>
                    <th class="py-3 px-5 font-semibold">Schedule</th>
                    <th class="py-3 px-5 font-semibold">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#778873]/20">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-[#A1BC98]/20 transition">
                        
                        {{-- CLIENT NAME --}}
                        <td class="py-3 px-5 font-medium text-[#3E3F29]">
                            {{ $booking->client->name ?? 'Guest User' }}
                        </td>

                        {{-- EVENT NAME --}}
                        <td class="py-3 px-5 text-gray-700">
                            {{ $booking->event->name ?? $booking->event_name ?? 'N/A' }}
                        </td>

                        {{-- COORDINATOR NAME --}}
                        <td class="py-3 px-5 text-gray-700">
                            {{ $booking->coordinator->coordinator_name ?? $booking->coordinator->user->name ?? 'Unassigned' }}
                        </td>

                        {{-- SCHEDULE (Date) --}}
                        <td class="py-3 px-5 text-gray-700">
                            {{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}
                        </td>

                        {{-- STATUS --}}
                        <td class="py-3 px-5">
                            <span class="inline-block px-3 py-1 text-xs rounded-full
                                {{ $booking->status === 'confirmed' || $booking->status === 'approved'
                                    ? 'bg-[#A1BC98] text-[#3E3F29]'
                                    : ($booking->status === 'cancelled' ? 'bg-red-300 text-white' : 'bg-yellow-200 text-yellow-800') }} 
                                font-medium">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 px-5 text-center text-gray-500">
                            No clients/bookings found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <div class="mt-6 py-4 no-print">
        <div class="flex justify-center">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    </div>

</div>

<style>
/* Print Header - Hidden by default, shown only in print */
.print-header {
    display: none;
}

@media print {
    /* Hide everything except print area */
    body * {
        visibility: hidden;
    }
    
    .print-area, .print-area * {
        visibility: visible;
    }
    
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    /* Show print header */
    .print-header {
        display: block;
        padding: 20px;
        border-bottom: 2px solid #A1BC98;
        margin-bottom: 20px;
    }

    /* Hide elements with no-print class */
    .no-print {
        display: none !important;
    }

    /* Reset backgrounds */
    body {
        background: white !important;
    }

    /* Table styling */
    table {
        border-collapse: collapse;
        width: 100%;
        page-break-inside: auto;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    th, td {
        border: 1px solid #A1BC98 !important;
        padding: 10px 14px !important;
    }

    th {
        background: #A1BC98 !important;
        color: #3E3F29 !important;
        font-weight: 700 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Status badges */
    .bg-yellow-200 {
        background: #fef08a !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .bg-red-300 {
        background: #fca5a5 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

@endsection