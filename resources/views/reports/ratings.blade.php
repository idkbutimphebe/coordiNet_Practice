@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between no-print">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
                Client Ratings & Feedback
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Feedback and ratings from clients per event
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

    <!-- ================= TABLE CARD ================= -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden print:shadow-none">

        <table class="w-full text-sm text-left">
            <thead class="bg-[#A1BC98]/40 text-[#3E3F29]">
                <tr>
                    <th class="py-3 px-5 font-semibold">#</th>
                    <th class="py-3 px-5 font-semibold">Client Name</th>
                    <th class="py-3 px-5 font-semibold">Event</th>
                    <th class="py-3 px-5 font-semibold">Coordinator</th>
                    <th class="py-3 px-5 font-semibold">Rating</th>
                    <th class="py-3 px-5 font-semibold">Feedback</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#778873]/20">
                @forelse ($ratings as $index => $booking)
                    <tr class="hover:bg-[#A1BC98]/20 transition print:hover:bg-transparent">
                        <td class="py-3 px-5 font-bold text-[#3E3F29]">
                            {{ $index + 1 }}
                        </td>

                        <td class="py-3 px-5 text-[#3E3F29]">
                            {{ $booking->client->name ?? '-' }}
                        </td>

                        <td class="py-3 px-5 text-[#3E3F29]">
                            {{ $booking->event->event_name ?? '-' }}
                        </td>

                        <td class="py-3 px-5 text-[#3E3F29]">
                            {{ $booking->coordinator->coordinator_name ?? '-' }}
                        </td>

                        <td class="py-3 px-5 font-bold text-[#3E3F29]">
                            {{ $booking->rating ? $booking->rating . ' ⭐' : 'N/A' }}
                        </td>

                        <td class="py-3 px-5 text-[#3E3F29]">
                            {{ $booking->feedback ?? 'No feedback' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 px-5 text-center text-gray-500">
                            No ratings found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

<!-- ================= PRINT STYLES ================= -->
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
