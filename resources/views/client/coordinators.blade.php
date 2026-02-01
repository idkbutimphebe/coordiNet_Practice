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

                    <!-- WEDDING -->
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

<script>
    function selectFilter(label) {
        document.getElementById('filterLabel').innerText = label;
        document.getElementById('filterDropdown').removeAttribute('open');
    }
</script>

<!-- COORDINATOR GRID -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

@foreach($coordinators as $coord)
<div class="bg-white rounded-2xl p-5 border shadow-sm">

    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-[#A1BC98]
                    flex items-center justify-center font-bold">
            {{ strtoupper(substr($coord->business_name ?? 'NA', 0, 2)) }}
        </div>

        <div>
            <p class="font-semibold">
                {{ $coord->business_name ?? 'No Business Name' }}
            </p>
            <p class="text-xs text-gray-500">
                ({{ $coord->reviews_count ?? 0 }} reviews)
            </p>
        </div>
    </div>

    <div class="mt-3 text-sm">
        Status:
        @php
            // ✅ Ensure $coord is always treated as Eloquent model
            $verified = $coord instanceof \Illuminate\Database\Eloquent\Model
                ? $coord->is_verified
                : ($coord['is_verified'] ?? false);
        @endphp
        <span class="{{ $verified ? 'text-green-600' : 'text-red-500' }}">
            {{ $verified ? 'Available' : 'Unavailable' }}
        </span>
    </div>

    <div class="mt-4 flex gap-3">
        <a href="{{ route('client.coordinators.profile', $coord->id) }}"
           class="flex-1 text-center py-2 border rounded">
            View
        </a>

         <form action="{{ route('client.coordinators.book', $coord->id) }}" method="POST" class="flex-1">
        @csrf
        <button type="submit"
                class="w-full py-2 bg-black text-white rounded">
            Book
        </button>
    </form>
    </div>

</div>
@endforeach

</div>
</div>
@endsection
