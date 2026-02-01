@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6">

    <!-- PAGE HEADER (SCREEN ONLY) -->
    <div class="flex items-center justify-between no-print">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
                Top 10 Coordinators
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Performance rankings by completed bookings
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

    <!-- ================= TABLE (PRINT AREA) ================= -->
    <div class="print-area bg-white rounded-2xl shadow-sm overflow-hidden">

        <table class="w-full text-sm text-left">
            <thead class="bg-[#A1BC98]/40 text-[#3E3F29]">
                <tr>
                    <th class="py-3 px-5 font-semibold">Rank</th>
                    <th class="py-3 px-5 font-semibold">Coordinator</th>
                    <th class="py-3 px-5 font-semibold text-center">Bookings</th>
                    <th class="py-3 px-5 font-semibold text-center">Rating</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#778873]/20">

                @forelse($topCoordinators as $index => $coordinator)
                <tr class="hover:bg-[#A1BC98]/20 transition">
                    <td class="py-3 px-5 font-bold text-[#3E3F29]">
                        #{{ $index + 1 }}
                    </td>

                    <td class="py-3 px-5 font-medium text-[#3E3F29]">
                        {{ $coordinator->coordinator->coordinator_name ?? '—' }}
                    </td>

                    <td class="py-3 px-5 text-center font-bold text-[#3E3F29]">
                        {{ $coordinator['bookings_count'] ?? 0 }}
                    </td>

                    <td class="py-3 px-5 text-center font-semibold text-[#3E3F29]">
                        {{ $coordinator['ratings_avg'] !== null ? number_format($coordinator['ratings_avg'], 1) : '0.0' }} ⭐
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 px-5 text-center text-gray-500">
                        No Top 10 Coordinators found.
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

    /* Hide everything */
    body * {
        visibility: hidden;
    }

    /* Show only table */
    .print-area,
    .print-area * {
        visibility: visible;
    }

    .print-area {
        position: absolute;
        inset: 0;
        width: 100%;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 12px;
    }

    th, td {
        border: 1px solid #A1BC98 !important;
        padding: 10px 16px !important;
    }

    th {
        background: #A1BC98 !important;
        color: #3E3F29 !important;
        font-weight: 700 !important;
    }

    tr:hover {
        background: transparent !important;
    }
}
</style>
@endsection
