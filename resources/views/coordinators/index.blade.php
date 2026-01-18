@extends('layouts.dashboard')

@section('content')

<div class="p-6 space-y-10">

    <!-- PAGE HEADER -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
            Coordinators
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            Coordinators grouped by event type
        </p>
    </div>

    @php
        $groups = [
            'birthday' => [
                ['JD','Juan Dela Cruz'],
                ['AM','April Martinez'],
                ['MK','Mark Kevin'],
            ],
            'wedding' => [
                ['LS','Lara Santos'],
                ['RT','Ryan Torres'],
                ['CP','Carla Perez'],
            ],
            'others' => [
                ['JN','Joshua Nunez'],
                ['MB','Maria Bello'],
                ['AL','Alex Lim'],
            ],
        ];
    @endphp

    @foreach($groups as $event => $items)

    <!-- EVENT SECTION -->
    <div class="space-y-4">

        <!-- TITLE -->
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-[#3E3F29] capitalize">
                {{ $event }} Coordinators
            </h2>

            <a href="{{ route('coordinators.event', $event) }}"
               class="text-sm font-medium text-[#778873] hover:underline">
                See all →
            </a>
        </div>

        <!-- CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($items as [$initials, $name])

            <div class="group bg-white rounded-xl p-4
                        border border-[#A1BC98]/40
                        shadow-sm
                        hover:shadow-lg hover:-translate-y-0.5
                        transition-all duration-300
                        flex items-center gap-4">

                <!-- ACCENT BAR -->
                <div class="w-1.5 h-10 rounded-full bg-[#778873]"></div>

                <!-- INITIALS -->
                <div class="w-11 h-11 rounded-lg
                            bg-[#A1BC98]/80
                            text-[#3E3F29]
                            flex items-center justify-center
                            font-bold text-sm
                            group-hover:bg-[#778873]/80
                            group-hover:text-white
                            transition">
                    {{ $initials }}
                </div>

                <!-- TEXT -->
                <div>
                    <p class="font-semibold text-[#3E3F29] leading-tight">
                        {{ $name }}
                    </p>
                    <p class="text-xs text-gray-500 capitalize">
                        {{ $event }} Coordinator
                    </p>
                </div>

            </div>

            @endforeach
        </div>

    </div>

    @endforeach

</div>

@endsection
