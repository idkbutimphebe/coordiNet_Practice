@extends('layouts.dashboard')

@section('content')

<!-- ================= GREETING ================= -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-[#3E3F29]">
        Good Morning, Admin!
    </h2>
    <p class="text-[#3E3F29]/70">
        Here’s what’s happening in your system today.
    </p>
</div>

<!-- ================= TOP STATS ================= -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    @php
        $stats = [
            ['label'=>'Total Users','value'=>'3,000','icon'=>'users'],
            ['label'=>'Total Bookings','value'=>'1,250','icon'=>'calendar'],
            ['label'=>'Pending Bookings','value'=>'50','icon'=>'clock'],
        ];
    @endphp

    @foreach($stats as $stat)
    <div class="bg-gradient-to-r from-[#778873] to-[#3E3F29]
                rounded-2xl text-white p-6 shadow
                flex items-center gap-4">

        <!-- ICON -->
        <div class="bg-white/20 p-3 rounded-full">
            @if($stat['icon'] === 'users')
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6 20v-1c0-2.76 2.24-5 5-5h2c2.76 0 5 2.24 5 5v1"/>
            </svg>

            @elseif($stat['icon'] === 'calendar')
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7
                         a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>

            @else
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            @endif
        </div>

        <!-- TEXT -->
        <div>
            <p class="text-sm opacity-90">{{ $stat['label'] }}</p>
            <h3 class="text-2xl font-bold">{{ $stat['value'] }}</h3>
        </div>
    </div>
    @endforeach

</div>

<!-- ================= FULL WIDTH CALENDAR ================= -->
<div class="bg-white rounded-2xl shadow p-6 mb-10">

    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-[#3E3F29]">
            Coordinator Availability Calendar
        </h3>
        <span class="text-sm text-[#778873]">
            This Month
        </span>
    </div>

    @php
        $availability = [
            3  => 'Available',
            5  => 'Booked',
            8  => 'Available',
            12 => 'Booked',
            15 => 'Available',
            18 => 'Booked',
            22 => 'Available',
        ];
    @endphp

    <!-- CALENDAR -->
    <div class="grid grid-cols-7 gap-3 text-center text-sm">

        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
            <div class="font-semibold text-[#778873]">
                {{ $day }}
            </div>
        @endforeach

        @for($i = 1; $i <= 31; $i++)
            <div class="p-4 rounded-xl text-xs font-semibold
                @if(isset($availability[$i]) && $availability[$i] === 'Available')
                    bg-[#A1BC98] text-[#3E3F29]
                @elseif(isset($availability[$i]) && $availability[$i] === 'Booked')
                    bg-[#3E3F29] text-white
                @else
                    bg-[#F6F8F5] text-gray-500
                @endif">

                {{ $i }}

                @if(isset($availability[$i]))
                    <div class="mt-1 text-[10px] font-normal">
                        {{ $availability[$i] }}
                    </div>
                @endif
            </div>
        @endfor
    </div>

    <!-- LEGEND -->
    <div class="flex gap-6 mt-6 text-xs">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 bg-[#A1BC98] rounded-full"></span>
            Available
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 bg-[#3E3F29] rounded-full"></span>
            Booked
        </div>
    </div>

</div>

@endsection
