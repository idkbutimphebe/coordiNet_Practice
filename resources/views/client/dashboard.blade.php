@extends('layouts.client')

@section('content')

<!-- ================= GREETING ================= -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-[#3E3F29]">
        Good Morning, {{ Auth::user()->name }}!
    </h2>
    <p class="text-[#3E3F29]/70">
        Here’s an overview of your events and bookings.
    </p>
</div>

<!-- ================= CLIENT TOP GRID ================= -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    <!-- ===== LEFT : MY EVENT BOOKINGS ===== -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">

        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-[#3E3F29]">
                My Event Bookings
            </h3>
            <span class="text-sm px-3 py-1 rounded-full bg-[#F6F8F5]
                         text-[#778873] font-semibold">
                Recent Events
            </span>
        </div>

        <!-- EMPTY STATE (INTENTIONAL) -->
        <div class="flex flex-col items-center justify-center
                    h-48 text-center text-[#778873]">

            <svg class="w-12 h-12 mb-3 opacity-60" fill="none"
                 stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3M4 11h16M5 5h14
                         a2 2 0 012 2v13
                         a2 2 0 01-2 2H5
                         a2 2 0 01-2-2V7
                         a2 2 0 012-2z"/>
            </svg>

            <p class="font-semibold">No event bookings yet</p>
            <p class="text-sm opacity-80">
                Your bookings will appear here once created.
            </p>
        </div>

    </div>

    <!-- ===== RIGHT : CLIENT STATS ===== -->
    <div class="space-y-4">

    @php
        $stats = [
            ['label' => 'My Bookings', 'value' => 0, 'icon' => 'calendar'],
            ['label' => 'Upcoming Events', 'value' => 0, 'icon' => 'clock'],
            ['label' => 'Completed Events', 'value' => 0, 'icon' => 'check'],
        ];
    @endphp

    @foreach($stats as $stat)
    <div class="bg-gradient-to-br from-[#778873] to-[#3E3F29]
                rounded-2xl text-white p-5 shadow
                flex items-center gap-4">

        <!-- ICON -->
        <div class="bg-white/20 p-3 rounded-full">
            @if($stat['icon'] === 'calendar')
            <!-- CALENDAR ICON -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7
                         a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>

            @elseif($stat['icon'] === 'clock')
            <!-- CLOCK ICON -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>

            @else
            <!-- CHECK ICON -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M5 13l4 4L19 7"/>
            </svg>
            @endif
        </div>

        <!-- TEXT -->
        <div>
            <p class="text-sm opacity-80">{{ $stat['label'] }}</p>
            <h3 class="text-3xl font-bold">{{ $stat['value'] }}</h3>
        </div>

    </div>
    @endforeach

</div>
    </div>

<!-- ================= CLIENT CHARTS + CALENDAR ================= -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

    <!-- BOOKING STATUS -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-bold text-[#3E3F29] mb-4">
            My Booking Status
        </h3>
        <canvas id="clientBookingChart" height="220"></canvas>
    </div>

    <!-- RATINGS -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-bold text-[#3E3F29] mb-4">
            My Submitted Ratings
        </h3>
        <canvas id="clientRatingChart" height="220"></canvas>
    </div>

    <!-- EVENT CALENDAR -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-bold text-[#3E3F29] mb-2">
            Event Calendar
        </h3>

        <div class="grid grid-cols-7 gap-2 text-center text-sm">
            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                <div class="font-semibold text-[#778873]">{{ $day }}</div>
            @endforeach

            @for($i = 1; $i <= 31; $i++)
                <div class="p-2 rounded-lg
                    {{ in_array($i, [15, 20])
                        ? 'bg-[#778873] text-white font-semibold'
                        : 'bg-[#F6F8F5]' }}">
                    {{ $i }}
                </div>
            @endfor
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    new Chart(document.getElementById('clientBookingChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Approved', 'Completed'],
            datasets: [{
                data: [1, 1, 2],
                backgroundColor: ['#A1BC98', '#778873', '#3E3F29']
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#3E3F29' }
                }
            }
        }
    });

    new Chart(document.getElementById('clientRatingChart'), {
        type: 'bar',
        data: {
            labels: ['Wedding', 'Birthday'],
            datasets: [{
                data: [5, 4],
                backgroundColor: '#778873'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: { legend: { display: false } }
        }
    });

});
</script>
@endpush
