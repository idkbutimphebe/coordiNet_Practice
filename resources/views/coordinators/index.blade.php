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


    <!-- SEARCH BAR + FILTER -->
    <form method="GET" class="flex flex-col md:flex-row gap-3 relative">

        <!-- SEARCH INPUT -->
        <input type="text"
               name="search"
               placeholder="Search by name or address..."
               value="{{ request('search') }}"
               class="w-full md:flex-1 px-4 py-3 rounded-lg
                      border border-[#A1BC98]
                      focus:ring-2 focus:ring-[#778873]
                      focus:outline-none text-sm">

        <!-- FILTER DROPDOWN -->
        <div class="flex gap-3 relative">
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
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12.414V19a1 1 0 01-.553.894l-4 2A1 1 0 019 21v-8.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    <span id="filterLabel">{{ request('event_type') ? ucfirst(request('event_type')) : 'All Events' }}</span>
                </summary>

                <!-- DROPDOWN -->
                <div class="absolute right-0 mt-2 w-56
                           bg-white rounded-xl shadow-xl
                           border border-gray-100 z-20">
                    @foreach(['wedding','birthday','others'] as $type)
                        <label class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-[#F6F8F5] cursor-pointer {{ $loop->first ? 'rounded-t-xl' : ($loop->last ? 'rounded-b-xl' : '') }}">
                            <input type="radio" name="event_type" value="{{ $type }}"
                                   class="hidden peer"
                                   onchange="this.form.submit()" 
                                   {{ request('event_type') == $type ? 'checked' : '' }}>
                            <span class="w-4 h-4 rounded-full border border-gray-400
                                         peer-checked:bg-[#3E3F29]"></span>
                            {{ ucfirst($type) }}
                        </label>
                    @endforeach
                </div>
            </details>

            <!-- SEARCH BUTTON -->
            <button type="submit"
                    class="px-8 py-3 rounded-lg
                           bg-[#3E3F29] text-white font-semibold text-sm
                           hover:opacity-90 transition">
                Search
            </button>
        </div>
    </form>

    <!-- ALL COORDINATOR CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        @forelse($coordinators as $event => $items)
            @foreach($items as $coordinator)
                <div class="group bg-white rounded-2xl p-5
                            border border-[#A1BC98]/40
                            shadow-sm
                            hover:shadow-lg hover:-translate-y-1
                            transition-all duration-300
                            flex items-center gap-6">

                    <!-- ACCENT BAR -->
                    <div class="w-2 h-12 rounded-full bg-[#778873]"></div>

                    <!-- INITIALS -->
                    <div class="w-16 h-16 rounded-lg
                                bg-[#A1BC98]/80
                                text-[#3E3F29]
                                flex items-center justify-center
                                font-bold text-lg
                                group-hover:bg-[#778873]/80
                                group-hover:text-white
                                transition">
                        {{ strtoupper(substr($coordinator->coordinator_name, 0, 2)) }}
                    </div>

                    <!-- INFO -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-[#3E3F29] text-lg leading-tight">
                            {{ $coordinator->coordinator_name }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ ucfirst($coordinator->event_type ?? 'Other') }} Coordinator
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $coordinator->address }}
                        </p>
                    </div>

                    <!-- VIEW BUTTON -->
                    <a href="{{ route('coordinators.show', $coordinator->id) }}"
                       class="inline-flex items-center gap-2
                              px-5 py-2 rounded-full
                              text-sm font-semibold
                              bg-[#778873] text-white
                              shadow-md
                              hover:bg-[#3E3F29]
                              hover:shadow-lg
                              transition-all">
                        View
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                </div>
            @endforeach
        @empty
            <div class="col-span-3 flex flex-col items-center justify-center mt-10 text-gray-500">
                <!-- PEOPLE ICON -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-[#A1BC98]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <p class="text-center text-lg">No coordinators found.</p>
            </div>
        @endforelse
    </div>

</div>

@endsection
