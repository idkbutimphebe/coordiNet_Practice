    @extends('layouts.client')

    @section('content')

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-[#3E3F29]">
            Good Morning, {{ Auth::user()->name }}!
        </h2>
        <p class="text-[#3E3F29]/70">
            Here’s an overview of your events and bookings.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

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

            <div class="bg-white/20 p-3 rounded-full">
                @if($stat['icon'] === 'calendar')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                @elseif($stat['icon'] === 'clock')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @else
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                @endif
            </div>

            <div>
                <p class="text-sm opacity-80">{{ $stat['label'] }}</p>
                <h3 class="text-3xl font-bold">{{ $stat['value'] }}</h3>
            </div>

        </div>
        @endforeach

        </div>
    </div>

    <div class="mb-10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-[#3E3F29]">
                Recommended Coordinators
            </h3>
            <a href="#" class="text-sm font-semibold text-[#778873] hover:underline">
                View All Coordinators &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">



    @foreach($coordinators as $coord)
    <div class="bg-white rounded-2xl shadow p-5 border border-[#F6F8F5]
                hover:shadow-lg hover:-translate-y-1 transition-all duration-300">

        <h4 class="text-lg font-bold text-[#3E3F29] mb-1">
            {{ $coord->business_name }}
        </h4>

        <span class="text-xs text-gray-500 ml-1">
            ({{ $coord->reviews_count ?? 0 }} reviews)
        </span>

        <a href="{{ route('client.coordinators.profile', $coord->id) }"
        class="block text-center w-full py-2 rounded-lg border-2 border-[#3E3F29]
                text-[#3E3F29] font-bold text-sm
                hover:bg-[#3E3F29] hover:text-white transition-colors">
            View Profile
        </a>
    </div>
    @endforeach


        </div>
    </div>

    @endsection

    @push('scripts')
    @endpush