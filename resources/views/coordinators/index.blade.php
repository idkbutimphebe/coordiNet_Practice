@extends('layouts.dashboard')

@section('content')

<div class="p-6 space-y-10">

    <!-- HEADER -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
            Coordinators
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            Coordinators grouped by event type
        </p>
    </div>

    <!-- SEARCH + FILTER -->
    <form method="GET" class="flex flex-col md:flex-row gap-3 relative">

        <input type="text"
               name="search"
               placeholder="Search by name or address..."
               value="{{ request('search') }}"
               class="w-full md:flex-1 px-4 py-3 rounded-lg
                      border border-[#A1BC98]
                      focus:ring-2 focus:ring-[#778873]
                      focus:outline-none text-sm">

        <div class="flex gap-3 relative">
            <details class="group">
                <summary
                    class="flex items-center gap-2 px-6 py-3 rounded-lg
                           bg-[#3E3F29] text-white font-semibold text-sm
                           cursor-pointer hover:opacity-90 transition
                           list-none">
                    <span>
                        {{ request('event_type') ? ucfirst(request('event_type')) : 'All Events' }}
                    </span>
                </summary>

                <div class="absolute right-0 mt-2 w-56
                            bg-white rounded-xl shadow-xl
                            border border-gray-100 z-20">
                    @foreach(['wedding','birthday','others'] as $type)
                        <label class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-[#F6F8F5] cursor-pointer">
                            <input type="radio"
                                   name="event_type"
                                   value="{{ $type }}"
                                   class="hidden peer"
                                   onchange="this.form.submit()"
                                   {{ request('event_type') == $type ? 'checked' : '' }}>
                            <span class="w-4 h-4 rounded-full border border-gray-400 peer-checked:bg-[#3E3F29]"></span>
                            {{ ucfirst($type) }}
                        </label>
                    @endforeach
                </div>
            </details>

            <button type="submit"
                    class="px-8 py-3 rounded-lg
                           bg-[#3E3F29] text-white font-semibold text-sm
                           hover:opacity-90 transition">
                Search
            </button>
        </div>
    </form>

    <!-- COORDINATORS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-10">

        @forelse($coordinators as $event => $items)
            @foreach($items as $coordinator)

                @if(is_object($coordinator))
                <div class="group relative bg-white rounded-3xl p-6
                            border border-[#A1BC98]/40 shadow-md
                            hover:shadow-2xl hover:-translate-y-2
                            transition-all duration-300">

                    <!-- glow -->
                    <div class="absolute inset-0 rounded-3xl
                                bg-gradient-to-br from-[#A1BC98]/20 to-[#778873]/20
                                opacity-0 group-hover:opacity-100
                                transition pointer-events-none"></div>

<!-- TOP -->
<div class="relative flex items-center gap-5">

    <!-- PROFILE IMAGE / FALLBACK INITIALS -->
    <div class="w-16 h-16 rounded-2xl overflow-hidden
                bg-gradient-to-br from-[#778873] to-[#3E3F29]
                flex items-center justify-center
                text-white font-extrabold text-xl
                shadow-lg shrink-0">

        @if(!empty($coordinator->profile_image))
            <img src="{{ asset('storage/coordinators/' . $coordinator->profile_image) }}"
                 alt="{{ $coordinator->user->name }}"
                 class="w-full h-full object-cover">
        @else
            {{ strtoupper(substr($coordinator->user->name ?? '??', 0, 2)) }}
        @endif
    </div>

    <!-- INFO -->
    <div class="flex-1 min-w-0">
        <h3 class="font-bold text-[#3E3F29] text-lg truncate">
            {{ $coordinator->user->name ?? 'Name Not Found' }}
        </h3>

        <p class="text-sm font-medium text-[#778873]">
            {{ ucfirst($event) }} Coordinator
        </p>

        <!-- EMAIL -->
        <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
            <svg class="w-4 h-4 text-[#778873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8
                         M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5
                         a2 2 0 01-2-2V8a2 2 0 012-2z"/>
            </svg>
            <span class="truncate">
                {{ $coordinator->user->email ?? 'email@notavailable.com' }}
            </span>
        </div>
    </div>
</div>


                    <!-- DIVIDER -->
                    <div class="my-5 h-px bg-gradient-to-r from-transparent via-[#A1BC98]/60 to-transparent"></div>

                    <!-- ACTION -->
                    <a href="{{ route('coordinators.show', ['event' => $event, 'id' => $coordinator->id]) }}"
                       class="inline-flex items-center justify-center gap-2 w-full
                              px-6 py-3 rounded-full text-sm font-semibold
                              text-white bg-gradient-to-r from-[#778873] to-[#3E3F29]
                              shadow-md hover:shadow-xl transition">
                        View Profile →
                    </a>
                </div>
                @endif

            @endforeach
        @empty
            <div class="col-span-3 text-center text-gray-500 mt-16">
                <p class="text-lg font-semibold">No coordinators found</p>
            </div>
        @endforelse

    </div>
</div>

@endsection
