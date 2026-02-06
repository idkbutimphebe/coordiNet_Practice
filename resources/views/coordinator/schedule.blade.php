@extends('layouts.coordinator')

@section('content')

<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29]">Schedule</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your appointments and availability.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-8 border border-gray-100">

        <div class="flex justify-between items-center mb-8">
            <h3 class="font-extrabold text-[#3E3F29] text-2xl tracking-tight">
                {{ $date->format('F Y') }}
            </h3>
            
            <div class="flex items-center gap-2 bg-[#F6F8F5] p-1 rounded-xl">
                <a href="{{ request()->fullUrlWithQuery(['month' => $prevMonth]) }}" 
                   class="p-2 hover:bg-white rounded-lg text-[#778873] hover:text-[#3E3F29] hover:shadow-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <span class="text-xs font-bold text-[#3E3F29] uppercase px-2">
                   Select Month
                </span>

                <a href="{{ request()->fullUrlWithQuery(['month' => $nextMonth]) }}" 
                   class="p-2 hover:bg-white rounded-lg text-[#778873] hover:text-[#3E3F29] hover:shadow-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-7 gap-3 text-center mb-4">
            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                <div class="font-bold text-[#B0B8A6] text-xs uppercase tracking-wider pb-2">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 gap-3">

            {{-- 1. Empty Slots for start of month --}}
            @for($i = 0; $i < $emptySlots; $i++)
                <div class="min-h-[100px]"></div>
            @endfor

            {{-- 2. Actual Days --}}
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $isBooked = in_array($day, $bookedDates);
                    $isToday = $date->copy()->setDay($day)->isToday();
                @endphp

                <div class="relative group p-3 rounded-2xl min-h-[100px] flex flex-col justify-between transition-all duration-300 border
                    {{-- Dynamic Classes based on status --}}
                    @if($isBooked)
                        bg-[#3E3F29] border-[#3E3F29] text-white shadow-lg shadow-[#3E3F29]/20
                    @else
                        bg-white border-gray-100 hover:border-[#A1BC98] hover:shadow-md text-[#3E3F29]
                    @endif
                ">
                    <div class="flex justify-between items-start">
                        <span class="text-lg font-bold {{ $isBooked ? 'text-white' : 'text-[#3E3F29]' }}">
                            {{ $day }}
                        </span>
                        
                        {{-- Dot Indicator for Today --}}
                        @if($isToday)
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                        @endif
                    </div>

                    <div class="mt-2">
                        @if($isBooked)
                            <div class="flex items-center gap-1.5 px-2 py-1.5 bg-white/10 rounded-lg backdrop-blur-sm w-fit">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#A1BC98]"></div>
                                <span class="text-[10px] font-bold uppercase tracking-wide text-[#F6F8F5]">Booked</span>
                            </div>
                        @else
                            <div class="flex items-center gap-1.5 px-2 py-1.5 bg-[#F6F8F5] rounded-lg w-fit group-hover:bg-[#A1BC98]/20 transition-colors">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#A1BC98]"></div>
                                <span class="text-[10px] font-bold uppercase tracking-wide text-[#778873]">Open</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endfor

        </div>

        <div class="flex gap-8 mt-10 pt-6 border-t border-gray-100 text-xs font-medium text-gray-500">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 border border-gray-200 bg-white rounded-full"></span>
                <span>Available</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-[#3E3F29] rounded-full shadow-sm"></span>
                <span class="text-[#3E3F29] font-bold">Booked / Busy</span>
            </div>
            <div class="flex items-center gap-2 ml-auto">
                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                <span>Today</span>
            </div>
        </div>

    </div>
</div>

@endsection