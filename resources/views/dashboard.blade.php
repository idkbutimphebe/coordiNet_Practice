@extends('layouts.dashboard')

@section('content')

<div class="mb-8">
    <h2 class="text-3xl font-bold text-[#3E3F29]">
        Good Morning, Admin!
    </h2>
    <p class="text-[#3E3F29]/70">
        Here’s what’s happening in your system today.
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    @php
        $stats = [
            ['label'=>'Total Users','value'=>'3,000','icon'=>'users'],
            ['label'=>'Total Bookings','value'=>'1,250','icon'=>'calendar'],
            ['label'=>'Pending Coordinators','value'=>'50','icon'=>'clock'],
        ];
    @endphp

    @foreach($stats as $stat)
    <a href="{{ $stat['route'] ?? '#' }}" 
       class="bg-gradient-to-r from-[#778873] to-[#3E3F29]
                rounded-2xl text-white p-6 shadow-md
                flex items-center gap-4 transition-all duration-300 
                hover:scale-[1.02] hover:shadow-lg active:scale-95 group">

        <div class="bg-white/20 p-3 rounded-full group-hover:bg-white/30 transition-colors">
            @if($stat['icon'] === 'users')
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 20v-1c0-2.76 2.24-5 5-5h2c2.76 0 5 2.24 5 5v1"/>
            </svg>

            @elseif($stat['icon'] === 'calendar')
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>

            @else
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            @endif
        </div>

        <div class="flex-1">
            <p class="text-sm opacity-90">{{ $stat['label'] }}</p>
            <h3 class="text-2xl font-bold">{{ $stat['value'] }}</h3>
        </div>

        <div class="opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </a>
    @endforeach

</div>

<div class="bg-white rounded-2xl shadow p-6 mb-10 border border-gray-100">

    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-[#3E3F29] text-lg">
            Coordinator Availability Calendar
        </h3>
        <span class="px-3 py-1 bg-[#F6F8F5] rounded-full text-xs font-medium text-[#778873]">
            {{ now()->format('F Y') }}
        </span>
    </div>

    <div class="grid grid-cols-7 gap-3 text-center text-sm">

        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
            <div class="font-bold text-[#778873] pb-2">
                {{ $day }}
            </div>
        @endforeach

        @for($i = 1; $i <= now()->daysInMonth; $i++)
            <div class="p-4 rounded-xl text-xs font-semibold min-h-[80px] flex flex-col items-center justify-center transition-colors
                @if(isset($availability[$i]) && $availability[$i] === 'Available')
                    bg-[#A1BC98] text-[#3E3F29]
                @elseif(isset($availability[$i]) && $availability[$i] === 'Booked')
                    bg-[#3E3F29] text-white
                @else
                    bg-[#F6F8F5] text-gray-400
                @endif">

                <span class="text-sm mb-1">{{ $i }}</span>

                @if(isset($availability[$i]) && $availability[$i] !== 'Available')
                    <div class="text-[10px] font-normal px-2 py-0.5 bg-white/10 rounded">
                        {{ $availability[$i] }}
                    </div>
                @endif
            </div>
        @endfor
    </div>

    <div class="flex gap-6 mt-8 pt-6 border-t border-gray-50 text-xs text-[#3E3F29]">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 bg-[#A1BC98] rounded-full"></span>
            <span class="font-medium">Available</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 bg-[#3E3F29] rounded-full"></span>
            <span class="font-medium">Booked / Event Scheduled</span>
        </div>
        <div class="flex items-center gap-2 ml-auto text-gray-400 italic">
            * Based on current bookings
        </div>
    </div>

</div>

@endsection