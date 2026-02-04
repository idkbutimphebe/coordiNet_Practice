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
                    <th class="py-3 px-5 font-semibold text-center">
                        Bookings
                    </th>
                    <th class="py-3 px-5 font-semibold text-center">
                        Rating
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#778873]/20">

                @php
                    $topCoordinators = [
                        ['1', 'Juan Dela Cruz', '28', '4.9 ⭐'],
                        ['2', 'April Martinez', '25', '4.8 ⭐'],
                        ['3', 'Mark Kevin', '23', '4.7 ⭐'],
                        ['4', 'Lara Santos', '21', '5.0 ⭐'],
                        ['5', 'Ryan Torres', '19', '4.6 ⭐'],
                        ['6', 'Carla Perez', '18', '4.9 ⭐'],
                        ['7', 'Joshua Nunez', '16', '4.8 ⭐'],
                        ['8', 'Maria Bello', '15', '4.7 ⭐'],
                        ['9', 'Alex Lim', '14', '4.9 ⭐'],
                        ['10', 'Anna Reyes', '12', '4.8 ⭐'],
                    ];
                @endphp

                @foreach($topCoordinators as [$rank, $name, $bookings, $rating])
                <tr class="hover:bg-[#A1BC98]/20 transition">
                    <td class="py-3 px-5 font-bold text-[#3E3F29]">
                        #{{ $rank }}
                    </td>

                    <td class="py-3 px-5 font-medium text-[#3E3F29]">
                        {{ $name }}
                    </td>

                    <td class="py-3 px-5 text-center font-bold text-[#3E3F29]">
                        {{ $bookings }}
                    </td>

                    <td class="py-3 px-5 text-center font-semibold text-[#3E3F29]">
                        {{ $rating }}
                    </td>
                </tr>
                @endforeach

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
