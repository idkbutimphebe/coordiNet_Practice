@extends('layouts.client')

@section('content')
<div class="space-y-8">

    <!-- PAGE HEADER -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
            Event Coordinators
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            Search, compare, and book professional event coordinators
        </p>
    </div>

    <!-- SEARCH BAR -->
    <div class="flex flex-col md:flex-row gap-3">
        <input type="text"
               placeholder="Search by service, location, or name..."
               class="w-full md:flex-1 px-4 py-3 rounded-lg
                      border border-[#A1BC98]
                      focus:ring-2 focus:ring-[#778873]
                      focus:outline-none text-sm">

        <button class="px-6 py-3 rounded-lg bg-[#3E3F29] text-white font-semibold">
            Search
        </button>
    </div>

    @php
        $groups = [
            'birthday' => [
                ['JD','Juan Dela Cruz', '4.8', 'Available'],
                ['AM','April Martinez', '4.6', 'Available'],
                ['MK','Mark Kevin', '4.5', 'Busy'],
            ],
            'wedding' => [
                ['LS','Lara Santos', '4.9', 'Available'],
                ['RT','Ryan Torres', '4.7', 'Available'],
                ['CP','Carla Perez', '4.6', 'Busy'],
            ],
            'others' => [
                ['JN','Joshua Nunez', '4.4', 'Available'],
                ['MB','Maria Bello', '4.5', 'Available'],
                ['AL','Alex Lim', '4.3', 'Busy'],
            ],
        ];
    @endphp

    @foreach($groups as $event => $items)

    <!-- EVENT SECTION -->
    <div class="space-y-4">

        <h2 class="text-lg font-semibold text-[#3E3F29] capitalize">
            {{ $event }} Coordinators
        </h2>

        <!-- CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @foreach($items as [$initials, $name, $rating, $status])

            <div class="bg-white rounded-2xl p-5
                        border border-[#A1BC98]/40
                        shadow-sm hover:shadow-lg transition">

                <!-- HEADER -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl
                                bg-[#A1BC98]
                                flex items-center justify-center
                                font-bold text-[#3E3F29]">
                        {{ $initials }}
                    </div>

                    <div>
                        <p class="font-semibold text-[#3E3F29]">
                            {{ $name }}
                        </p>
                        <p class="text-xs text-gray-500 capitalize">
                            {{ $event }} events
                        </p>
                    </div>
                </div>

                <!-- INFO -->
                <div class="mt-4 space-y-1 text-sm">
                    <p>⭐ Rating: <strong>{{ $rating }}</strong></p>
                    <p>
                        Status:
                        <span class="font-medium
                            {{ $status === 'Available' ? 'text-green-600' : 'text-red-500' }}">
                            {{ $status }}
                        </span>
                    </p>
                </div>

                <!-- ACTIONS -->
                <div class="mt-5 flex gap-3">
                <a href="{{ route('client.coordinators.view', $name) }}"
                class="flex-1 text-center py-2 rounded-lg
                        border border-[#778873]
                        text-[#778873]
                        text-sm font-medium
                        hover:bg-[#778873]/10 transition">
                    View
                </a>


                    <a href="{{ route('client.bookings.index') }}"
                       class="flex-1 text-center py-2 rounded-lg
                              bg-[#3E3F29] text-white
                              text-sm font-semibold
                              hover:opacity-90 transition">
                        Book
                    </a>
                </div>

            </div>

            @endforeach

        </div>

    </div>

    @endforeach

</div>
@endsection
