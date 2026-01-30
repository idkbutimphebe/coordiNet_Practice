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

<!-- SEARCH BAR + FILTER -->
<div class="flex flex-col md:flex-row gap-3 relative">

    <!-- SEARCH INPUT -->
    <input type="text"
           name="search"
           placeholder="Search by name or location..."
           class="w-full md:flex-1 px-4 py-3 rounded-lg
                  border border-[#A1BC98]
                  focus:ring-2 focus:ring-[#778873]
                  focus:outline-none text-sm">

    <!-- BUTTONS -->
    <div class="flex gap-3 relative">

        <!-- FILTER DROPDOWN -->
        <div class="relative">

            <details id="filterDropdown" class="group">
                <summary
                    class="flex items-center gap-2 px-6 py-3 rounded-lg
                           bg-[#3E3F29] text-white font-semibold text-sm
                           cursor-pointer hover:opacity-90 transition
                           list-none">

                    <!-- ICON -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2
                                 a1 1 0 01-.293.707L15 12.414V19
                                 a1 1 0 01-.553.894l-4 2A1
                                 1 0 019 21v-8.586L3.293
                                 6.707A1 1 0 013 6V4z"/>
                    </svg>

                    <span id="filterLabel">All Events</span>
                </summary>

                <!-- DROPDOWN -->
                <div
                    class="absolute right-0 mt-2 w-56
                           bg-white rounded-xl shadow-xl
                           border border-gray-100 z-20">

                    <!-- WEDDING (MOST CUSTOMERS - FIRST) -->
                    <label class="flex items-center gap-3 px-4 py-3 text-sm
                                  hover:bg-[#F6F8F5] cursor-pointer rounded-t-xl">
                        <input type="radio" name="event_type" value="wedding"
                               class="hidden peer"
                               onclick="selectFilter('Wedding')">
                        <span class="w-4 h-4 rounded-full border border-gray-400
                                     peer-checked:bg-[#3E3F29]"></span>
                        Wedding
                    </label>

                    <!-- BIRTHDAY -->
                    <label class="flex items-center gap-3 px-4 py-3 text-sm
                                  hover:bg-[#F6F8F5] cursor-pointer">
                        <input type="radio" name="event_type" value="birthday"
                               class="hidden peer"
                               onclick="selectFilter('Birthday')">
                        <span class="w-4 h-4 rounded-full border border-gray-400
                                     peer-checked:bg-[#3E3F29]"></span>
                        Birthday 
                    </label>

                    <!-- OTHERS -->
                    <label class="flex items-center gap-3 px-4 py-3 text-sm
                                  hover:bg-[#F6F8F5] cursor-pointer rounded-b-xl">
                        <input type="radio" name="event_type" value="others"
                               class="hidden peer"
                               onclick="selectFilter('Others')">
                        <span class="w-4 h-4 rounded-full border border-gray-400
                                     peer-checked:bg-[#3E3F29]"></span>
                        Other Events
                    </label>

                </div>
            </details>

        </div>

        <!-- SEARCH BUTTON -->
        <button type="submit"
                class="px-8 py-3 rounded-lg
                       bg-[#3E3F29] text-white font-semibold text-sm
                       hover:opacity-90 transition">
            Search
        </button>

    </div>
</div>

<!-- SMALL SCRIPT -->
<script>
    function selectFilter(label) {
        document.getElementById('filterLabel').innerText = label;
        document.getElementById('filterDropdown').removeAttribute('open');
    }
</script>



    @php
        $coordinators = [
            ['JD','Juan Dela Cruz', 'Birthday', '4.8', 'Available'],
            ['AM','April Martinez', 'Birthday', '4.6', 'Available'],
            ['MK','Mark Kevin', 'Birthday', '4.5', 'Busy'],

            ['LS','Lara Santos', 'Wedding', '4.9', 'Available'],
            ['RT','Ryan Torres', 'Wedding', '4.7', 'Available'],
            ['CP','Carla Perez', 'Wedding', '4.6', 'Busy'],

            ['JN','Joshua Nunez', 'Others', '4.4', 'Available'],
            ['MB','Maria Bello', 'Others', '4.5', 'Available'],
            ['AL','Alex Lim', 'Others', '4.3', 'Busy'],
        ];
    @endphp

    <!-- COORDINATOR GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @foreach($coordinators as [$initials, $name, $type, $rating, $status])

        <div class="bg-white rounded-2xl p-5
                    border border-[#A1BC98]/40
                    shadow-sm hover:shadow-xl
                    transition-all hover:-translate-y-1">

            <!-- HEADER -->
            <div class="flex items-center justify-between">

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
                        <p class="text-xs text-gray-500">
                            ⭐ {{ $rating }} rating
                        </p>
                    </div>
                </div>

                <!-- EVENT TAG -->
                <span class="px-3 py-1 text-xs rounded-full
                             bg-[#778873]/15 text-[#3E3F29]
                             font-semibold">
                    {{ $type }}
                </span>
            </div>

            <!-- STATUS -->
            <div class="mt-4 text-sm">
                Status:
                <span class="font-medium
                    {{ $status === 'Available' ? 'text-green-600' : 'text-red-500' }}">
                    {{ $status }}
                </span>
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
@endsection
