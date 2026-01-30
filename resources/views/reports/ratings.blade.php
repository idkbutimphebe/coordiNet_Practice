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
                @php
                    $ratings = [
                        ['1','Jan Tirzuh Santos','Birthday','Juan Dela Cruz','5⭐','Great service, very professional!'],
                        ['2','Maria Lopez','Wedding','April Martinez','4.8⭐','Everything went smoothly.'],
                        ['3','John Reyes','Birthday','Mark Kevin','4.5⭐','Coordinator was very helpful.'],
                        ['4','Anna Cruz','Wedding','Lara Santos','5⭐','Perfectly organized!'],
                        ['5','Kevin Ramos','Others','Ryan Torres','4.7⭐','Good experience overall.'],
                        ['6','Ella Gomez','Wedding','Paulo Reyes','4.9⭐','Highly recommend!'],
                        ['7','Chris Mendoza','Birthday','Leo Navarro','4.6⭐','Everything went well.'],
                        ['8','Sofia Lim','Others','Kurt Valdez','4.8⭐','Coordinator was attentive.'],
                        ['9','Mark Dizon','Birthday','Neil Ramos','5⭐','Exceeded expectations!'],
                        ['10','Paula Reyes','Wedding','Ivy Santos','4.7⭐','Very satisfied.'],
                    ];
                @endphp

                @foreach($ratings as [$id, $client, $event, $coordinator, $rating, $feedback])
                <tr class="hover:bg-[#A1BC98]/20 transition print:hover:bg-transparent">
                    <td class="py-3 px-5 font-bold text-[#3E3F29]">{{ $id }}</td>
                    <td class="py-3 px-5 text-[#3E3F29]">{{ $client }}</td>
                    <td class="py-3 px-5 text-[#3E3F29]">{{ $event }}</td>
                    <td class="py-3 px-5 text-[#3E3F29]">{{ $coordinator }}</td>
                    <td class="py-3 px-5 font-bold text-[#3E3F29]">{{ $rating }}</td>
                    <td class="py-3 px-5 text-[#3E3F29]">{{ $feedback }}</td>
                </tr>
                @endforeach
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
