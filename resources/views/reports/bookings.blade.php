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

    <!-- ================= TABLE CARD ================= -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden print:shadow-none print-only">

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
                @php
                    $bookings = [
                        ['1','Jan Tirzuh Santos','Birthday','Juan Dela Cruz','Mar 10, 2025','Approved'],
                        ['2','Maria Lopez','Wedding','April Martinez','Apr 18, 2025','Approved'],
                        ['3','John Reyes','Birthday','Mark Kevin','May 02, 2025','Pending'],
                        ['4','Anna Cruz','Wedding','Lara Santos','May 21, 2025','Approved'],
                        ['5','Kevin Ramos','Others','Ryan Torres','Jun 08, 2025','Approved'],
                    ];
                @endphp

                @foreach($bookings as [$id, $client, $event, $coordinator, $schedule, $status])
                <tr class="hover:bg-[#A1BC98]/20 transition print:hover:bg-transparent">
                    <td class="py-3 px-5 font-bold text-[#3E3F29]">{{ $id }}</td>
                    <td class="py-3 px-5 text-[#3E3F29]">{{ $client }}</td>
                    <td class="py-3 px-5 text-gray-700">{{ $event }}</td>
                    <td class="py-3 px-5 text-gray-700">{{ $coordinator }}</td>
                    <td class="py-3 px-5 text-gray-700">{{ $schedule }}</td>
                    <td class="py-3 px-5">
                        <span class="inline-block px-3 py-1 text-xs rounded-full
                            {{ $status === 'Approved'
                                ? 'bg-[#A1BC98] text-[#3E3F29]'
                                : 'bg-yellow-200 text-yellow-800' }}">
                            {{ $status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>

<!-- ================= PRINT RULES (SAME AS YOUR WORKING ONES) ================= -->
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
