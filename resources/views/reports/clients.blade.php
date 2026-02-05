@extends('layouts.dashboard')

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

    <div class="print-area bg-white rounded-2xl shadow-sm overflow-hidden">

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
                {{-- Loop through Bookings instead of Clients --}}
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
            {{ $bookings->links() }}
        </div>
    </div>

</div>

<style>
@media print {
    body * { visibility: hidden; }
    .print-area, .print-area * { visibility: visible; }
    .print-area { position: absolute; top: 0; left: 0; width: 100%; }
    table { border-collapse: collapse; width: 100%; font-size: 12px; }
    th, td { border: 1px solid #A1BC98 !important; padding: 10px 14px !important; }
    th { background: #A1BC98 !important; color: #3E3F29 !important; font-weight: 700 !important; }
    tr:hover { background: transparent !important; }
    
    /* Hide pagination in print */
    .no-print { display: none !important; }
}
</style>

@endsection