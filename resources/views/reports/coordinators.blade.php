@extends('layouts.dashboard')

@section('content')

<div class="p-6 space-y-10">

    <!-- ================= PAGE HEADER ================= -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
                Coordinator Reports
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Performance and activity of event coordinators
            </p>
        </div>

        <a href="{{ route('reports') }}"
           class="px-4 py-2 text-sm rounded-lg
                  border border-[#778873]
                  text-[#3E3F29]
                  hover:bg-[#A1BC98]/40 transition">
            ← Back
        </a>
    </div>

    <!-- ================= TOP 10 COORDINATORS ================= -->
<div class="space-y-5">

    <!-- TITLE -->
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-[#778873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  d="M12 8l2 4 4 .5-3 3 .7 4.5-3.7-2-3.7 2 .7-4.5-3-3 4-.5 2-4z"/>
        </svg>
        <h2 class="text-lg font-semibold text-[#3E3F29]">
            Top 10 Event Coordinators
        </h2>
    </div>

    <!-- EXPLANATION BOX -->
    <div class="bg-[#A1BC98]/25 border border-[#A1BC98]/40
                rounded-xl p-4 text-sm text-[#3E3F29]">

        <p class="font-semibold mb-2">
            How Top Coordinators are ranked:
        </p>

        <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#778873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
                Number of completed bookings
            </li>

            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#778873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
                Consistent event participation
            </li>

            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#778873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
                High coordinator availability
            </li>

            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#778873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
                Positive performance history
            </li>

            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#778873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
                Active status in the system
            </li>
        </ul>
    </div>

    <!-- TOP 10 CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">

        @php
            $topCoordinators = [
                ['JD','Juan Dela Cruz', 24],
                ['AM','April Martinez', 21],
                ['MK','Mark Kevin', 19],
                ['LS','Lara Santos', 18],
                ['RT','Ryan Torres', 17],
                ['RT','Ryan Torres', 17],
                ['RT','Ryan Torres', 17],
                ['RT','Ryan Torres', 17],
                ['RT','Ryan Torres', 17],
                ['RT','Ryan Torres', 17],

            ];
        @endphp

        @foreach($topCoordinators as [$initials, $name, $count])
        <div
            class="bg-gradient-to-br from-[#778873] to-[#3E3F29]
                   text-white rounded-2xl p-4 shadow-md
                   hover:scale-[1.03] transition">

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/20
                            flex items-center justify-center
                            font-bold">
                    {{ $initials }}
                </div>

                <div>
                    <p class="font-semibold leading-tight">
                        {{ $name }}
                    </p>
                    <p class="text-xs opacity-80">
                        {{ $count }} bookings
                    </p>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>


    <!-- ================= ALL COORDINATORS ================= -->
    <div class="space-y-4">

        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-[#778873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      d="M17 20h5v-2a4 4 0 00-4-4h-1
                         M9 20H4v-2a4 4 0 014-4h1
                         M12 12a4 4 0 100-8 4 4 0 000 8z"/>
            </svg>
            <h2 class="text-lg font-semibold text-[#3E3F29]">
                All Coordinators
            </h2>
        </div>

        <!-- ================= TABLE CARD ================= -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

            <table class="w-full text-sm text-left">
                <thead class="bg-[#A1BC98]/40 text-[#3E3F29]">
                    <tr>
                        <th class="py-3 px-5 font-semibold">Coordinator</th>
                        <th class="py-3 px-5 font-semibold">Event Type</th>
                        <th class="py-3 px-5 font-semibold">Bookings</th>
                        <th class="py-3 px-5 font-semibold">Status</th>
                        <th class="py-3 px-5 font-semibold text-center">View</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#778873]/20">

                    @php
                        $allCoordinators = [
                            ['Juan Dela Cruz','Birthday',24,'Active'],
                            ['April Martinez','Wedding',21,'Active'],
                            ['Mark Kevin','Others',19,'Active'],
                            ['Lara Santos','Wedding',15,'Inactive'],
                        ];
                    @endphp

                    @foreach($allCoordinators as [$name, $event, $count, $status])
                    <tr class="hover:bg-[#A1BC98]/20 transition">

                        <td class="py-3 px-5 font-medium text-[#3E3F29]">
                            {{ $name }}
                        </td>

                        <td class="py-3 px-5 text-gray-700">
                            {{ $event }}
                        </td>

                        <td class="py-3 px-5 text-gray-700">
                            {{ $count }}
                        </td>

                        <td class="py-3 px-5">
                            <span class="inline-block px-3 py-1 text-xs rounded-full font-medium
                                {{ $status === 'Active'
                                    ? 'bg-[#A1BC98] text-[#3E3F29]'
                                    : 'bg-gray-200 text-gray-600' }}">
                                {{ $status }}
                            </span>
                        </td>

                        <td class="py-3 px-5 text-center">
                            <a href="#"
                               class="inline-flex items-center gap-1.5
                                      px-4 py-1.5 text-xs rounded-lg
                                      bg-[#778873] text-white
                                      hover:bg-[#3E3F29] transition">
                                View
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>

                    </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection
