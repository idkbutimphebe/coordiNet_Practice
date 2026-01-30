@extends('layouts.coordinator')

@section('content')

<!-- ================= GREETING ================= -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-[#3E3F29]">
        Good Morning, Coordinator!
    </h2>
    <p class="text-[#3E3F29]/70">
        Here’s what’s happening in your system today.
    </p>
</div>

<!-- ================= TOP GRID ================= -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
<div class="lg:col-span-2 bg-white rounded-2xl shadow p-5">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-[#3E3F29]">
            Pending Requests
        </h3>
        <a href="#" class="text-sm text-[#778873] hover:underline">
            See All
        </a>
    </div>

    @php
        $requests = [
            ['name'=>'mamamo', 'type'=>'Coordinator Application', 'date'=>'Oct 24, 2025'],
            ['name'=>'mamako', 'type'=>'Wedding Booking', 'date'=>'Oct 23, 2025'],
            ['name'=>'Strawberry', 'type'=>'Coordinator Application', 'date'=>'Oct 22, 2025'],
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($requests as $r)
        <div class="rounded-xl overflow-hidden shadow-sm bg-[#F6F8F5] group hover:-translate-y-1 transition-transform duration-300">

            <div class="h-24 bg-[#A1BC98] flex items-end justify-center pb-[-2rem]">
                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode($r['name']) }}&background=F2F4F1&color=3E3F29"
                    class="w-16 h-16 rounded-full border-4 border-[#F6F8F5] shadow-md translate-y-8"
                >
            </div>

            <div class="pt-10 pb-5 px-3 text-center">
                <h4 class="text-sm font-bold text-[#3E3F29] mb-1">
                    {{ $r['name'] }}
                </h4>
                
                <span class="inline-block px-2 py-0.5 rounded-md bg-[#3E3F29]/10 text-[#3E3F29] text-[10px] font-bold uppercase tracking-wider mb-2">
                    {{ $r['type'] }}
                </span>

                <div class="text-xs text-[#778873] font-medium flex items-center justify-center gap-1 mt-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Requested: {{ $r['date'] }}</span>
                </div>
            </div>

        </div>
        @endforeach
    </div>
</div>

     <!-- ===== RIGHT : OVERVIEW CARDS ===== -->
    <div class="space-y-4">
        @php
            $stats = [
                [
                    'label' => 'Total Users',
                    'value' => '3,000',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>'
                ],
                [
                    'label' => 'Pending Bookings',
                    'value' => '50',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                ],
                [
                    'label' => 'Active Coordinators',
                    'value' => '200',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7"/>'
                ],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="flex items-center gap-4
                    bg-gradient-to-r from-[#778873] to-[#3E3F29]
                    rounded-xl text-white p-5 shadow">

            <div class="w-12 h-12 flex items-center justify-center
                        rounded-lg bg-white/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $stat['icon'] !!}
                </svg>
            </div>

            <div>
                <p class="text-sm opacity-90">{{ $stat['label'] }}</p>
                <h3 class="text-2xl font-bold">{{ $stat['value'] }}</h3>
            </div>
        </div>
        @endforeach
    </div>

</div>

<!-- ================= CHARTS ================= -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    <div class="lg:col-span-2 bg-white rounded-2xl shadow p-5">
        <h3 class="font-bold text-[#3E3F29] mb-4">Booking Activity</h3>
        <canvas id="bookingChart" height="120"></canvas>
    </div>

    <div class="bg-white rounded-2xl shadow p-5">
        <h3 class="font-bold text-[#3E3F29] mb-4">Booking Status</h3>
        <canvas id="statusChart" height="220"></canvas>
    </div>
</div>

@endsection
