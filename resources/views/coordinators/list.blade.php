@extends('layouts.dashboard')

@section('content')

<div class="p-6 space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight capitalize">
                {{ $event }} Coordinators
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Coordinators assigned to {{ ucfirst($event) }} events
            </p>
        </div>

        <!-- BACK BUTTON -->
        <a href="{{ route('coordinators') }}"
           class="px-4 py-2 text-sm rounded-lg
                  border border-[#778873]
                  text-[#3E3F29]
                  hover:bg-[#A1BC98]/40 transition">
            ← Back
        </a>
    </div>

    <!-- SEARCH BAR -->
    <div class="flex items-center gap-3">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#3E3F29]/60">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-4.35-4.35m1.85-5.4a7.25 7.25 0 11-14.5 0 7.25 7.25 0 0114.5 0z"/>
                </svg>
            </span>

            <input
                type="text"
                placeholder="Search coordinators..."
                class="w-full pl-10 pr-4 py-3 rounded-lg
                       bg-white border border-[#A1BC98]
                       text-sm text-[#3E3F29]
                       placeholder-[#3E3F29]/60
                       focus:outline-none focus:ring-2
                       focus:ring-[#778873]"
            >
        </div>

        <button
            class="px-6 py-3 rounded-lg
                   bg-[#3E3F29] text-white
                   text-sm font-semibold
                   hover:opacity-90 transition">
            Search
        </button>
    </div>

    <!-- COORDINATOR GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @php
            $coordinators = [
                ['JD', 'Juan Dela Cruz', 1],
                ['AM', 'April Martinez', 2],
                ['MK', 'Mark Kevin', 3],
            ];
        @endphp

        @foreach($coordinators as [$initials, $name, $id])
        <div
            class="group bg-white rounded-xl p-5
                   border border-[#A1BC98]/40
                   shadow-sm
                   hover:shadow-lg hover:-translate-y-0.5
                   transition-all duration-300">

            <div class="flex items-center gap-4">

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

                <!-- INFO -->
                <div class="flex-1">
                    <h3 class="font-semibold text-[#3E3F29] leading-tight">
                        {{ $name }}
                    </h3>
                    <p class="text-xs text-gray-500">
                        {{ ucfirst($event) }} Coordinator
                    </p>
                </div>

                <!-- VIEW -->
<a href="{{ route('coordinators.show', ['event' => $event, 'id' => $id]) }}"
   class="inline-flex items-center gap-1.5
          px-4 py-1.5 rounded-full
          text-xs font-semibold
          bg-[#778873] text-white
          shadow-md
          hover:bg-[#3E3F29]
          hover:shadow-lg
          transition-all">

    View
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M9 5l7 7-7 7"/>
    </svg>
</a>


            </div>
        </div>
        @endforeach

    </div>

</div>

@endsection
