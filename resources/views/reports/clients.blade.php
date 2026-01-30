@extends('layouts.dashboard')

@section('content')

<div class="p-6 space-y-8">

    <!-- PAGE HEADER (SCREEN ONLY) -->
    <div class="flex items-center justify-between no-print">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
                All Clients
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Approved clients and their assigned events
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

    <!-- ================= PRINT AREA ================= -->
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
                @php
                    $clients = [
                        ['Jan Tirzuh Santos','Birthday','Juan Dela Cruz','Mar 10, 2025'],
                        ['Maria Lopez','Wedding','April Martinez','Apr 18, 2025'],
                        ['John Reyes','Birthday','Mark Kevin','May 02, 2025'],
                        ['Anna Cruz','Wedding','Lara Santos','May 21, 2025'],
                        ['Kevin Ramos','Others','Ryan Torres','Jun 08, 2025'],
                        ['Ella Gomez','Wedding','Paulo Reyes','Jun 14, 2025'],
                        ['Chris Mendoza','Birthday','Leo Navarro','Jul 01, 2025'],
                        ['Sofia Lim','Others','Kurt Valdez','Jul 19, 2025'],
                        ['Mark Dizon','Birthday','Neil Ramos','Aug 03, 2025'],
                        ['Paula Reyes','Wedding','Ivy Santos','Aug 22, 2025'],
                    ];
                @endphp

                @foreach($clients as [$name, $event, $coordinator, $schedule])
                <tr>
                    <td class="py-3 px-5 font-medium text-[#3E3F29]">
                        {{ $name }}
                    </td>

                    <td class="py-3 px-5 text-gray-700">
                        {{ $event }}
                    </td>

                    <td class="py-3 px-5 text-gray-700">
                        {{ $coordinator }}
                    </td>

                    <td class="py-3 px-5 text-gray-700">
                        {{ $schedule }}
                    </td>

                    <td class="py-3 px-5">
                        <span class="inline-block px-3 py-1 text-xs rounded-full
                                     bg-[#A1BC98] text-[#3E3F29] font-medium">
                            Approved
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <!-- PAGINATION (SCREEN ONLY) -->
    <div class="mt-6 py-4 no-print">
        <div class="flex justify-center">
            <nav class="flex items-center gap-2 text-sm">

                <button disabled
                        class="px-2.5 py-1.5 rounded-md bg-[#778873]
                               text-white opacity-40">
                    ‹
                </button>

                <button
                        class="px-3 py-1.5 rounded-md bg-[#3E3F29]
                               text-white font-medium">
                    1
                </button>

                <button
                        class="px-3 py-1.5 rounded-md bg-[#A1BC98]
                               text-[#3E3F29]
                               hover:bg-[#778873] hover:text-white transition">
                    2
                </button>

                <button
                        class="px-2.5 py-1.5 rounded-md bg-[#778873]
                               text-white hover:bg-[#3E3F29] transition">
                    ›
                </button>

            </nav>
        </div>
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
        top: 0;
        left: 0;
        width: 100%;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid #A1BC98 !important;
        padding: 12px 18px !important;
    }

    th {
        background: #A1BC98 !important;
        color: #3E3F29 !important;
        font-weight: 700 !important;
    }
}
</style>

@endsection
