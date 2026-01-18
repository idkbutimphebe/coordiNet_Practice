@extends('layouts.coordinator')

@section('content')

<!-- ================= GREETING ================= -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-[#3E3F29]">
        Good Morning, {{ Auth::user()->name }}!
    </h2>
    <p class="text-[#3E3F29]/70">
        Here’s an overview of your assigned events and performance.
    </p>
</div>

<!-- ================= TOP STATS ================= -->
@php
    $stats = [
        ['label'=>'Total Bookings','value'=>12,'icon'=>'calendar'],
        ['label'=>'Upcoming Events','value'=>8,'icon'=>'clock'],
        ['label'=>'Completed Events','value'=>3,'icon'=>'check'],
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    @foreach($stats as $stat)
        <div class="bg-gradient-to-r from-[#778873] to-[#3E3F29]
                    rounded-2xl text-white p-6 shadow
                    flex items-center gap-4">

            <!-- ICON -->
            <div class="bg-white/20 p-3 rounded-full">
                @if($stat['icon'] === 'calendar')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14
                                 a2 2 0 002-2V7
                                 a2 2 0 00-2-2H5
                                 a2 2 0 00-2 2v12
                                 a2 2 0 002 2z"/>
                    </svg>
                @elseif($stat['icon'] === 'clock')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8v4l3 3
                                 m6-3a9 9 0 11-18 0
                                 9 9 0 0118 0z"/>
                    </svg>
                @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
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

<!-- ================= ASSIGNED EVENTS ================= -->
@php
    $events = [
        ['title'=>'Wedding Event','date'=>'March 15, 2025','status'=>'Upcoming'],
        ['title'=>'Birthday Party','date'=>'March 20, 2025','status'=>'Upcoming'],
        ['title'=>'Corporate Meeting','date'=>'March 01, 2025','status'=>'Completed'],
    ];
@endphp

<div class="bg-gradient-to-r from-[#A1BC98]/40 to-[#F6F8F5]
            rounded-2xl shadow p-6 mb-10">

    <h3 class="font-bold text-[#3E3F29] mb-4">
        Assigned Events
    </h3>

    <div class="space-y-4">
        @foreach($events as $event)
            <div class="flex justify-between items-center
                        bg-[#F6F8F5] rounded-xl p-4">

                <div>
                    <p class="font-semibold text-[#3E3F29]">
                        {{ $event['title'] }}
                    </p>
                    <p class="text-sm text-gray-600">
                        {{ $event['date'] }}
                    </p>
                </div>

                <span class="px-4 py-1.5 text-xs rounded-full font-semibold
                    {{ $event['status'] === 'Completed'
                        ? 'bg-[#3E3F29] text-white'
                        : 'bg-[#A1BC98] text-[#3E3F29]' }}">
                    {{ $event['status'] }}
                </span>
            </div>
        @endforeach
    </div>
</div>

<!-- ================= PERFORMANCE SUMMARY ================= -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-gradient-to-br from-[#A1BC98] to-[#778873]
                rounded-2xl p-6 text-[#3E3F29] shadow">
        <p class="text-sm opacity-80">Client Satisfaction</p>
        <h2 class="text-3xl font-bold mt-2">90%</h2>
    </div>

    <div class="bg-gradient-to-br from-[#778873] to-[#3E3F29]
                rounded-2xl p-6 text-white shadow">
        <p class="text-sm opacity-80">Average Rating</p>
        <h2 class="text-3xl font-bold mt-2">4.8 ⭐</h2>
    </div>

    <div class="bg-gradient-to-br from-[#A1BC98]/70 to-[#E9F0E6]
                rounded-2xl p-6 text-[#3E3F29] shadow">
        <p class="text-sm opacity-80">On-time Events</p>
        <h2 class="text-3xl font-bold mt-2">100%</h2>
    </div>

</div>

@endsection
