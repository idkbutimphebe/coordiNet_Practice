@extends('layouts.coordinator')

@section('content')

<!-- FULL-WIDTH CONTENT WRAPPER -->
<div class="w-full min-h-screen px-8 py-6 space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
            Bookings
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            Manage and review booking requests.
        </p>
    </div>

    <!-- SEARCH -->
    <div class="flex items-center gap-3 w-full">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#3E3F29]/60">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-4.35-4.35m1.85-5.4a7.25 7.25 0 11-14.5 0 7.25 7.25 0 0114.5 0z"/>
                </svg>
            </span>

            <input
                type="text"
                placeholder="Search bookings..."
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

    <!-- TABLE CARD (FULL WIDTH) -->
    <div class="w-full bg-white rounded-2xl shadow-sm overflow-x-auto">

        <table class="w-full text-sm text-left">
            <thead class="bg-[#A1BC98]/40 text-[#3E3F29]">
                <tr>
                    <th class="py-3 px-6 font-semibold">Name</th>
                    <th class="py-3 px-6 font-semibold">Event Requested</th>
                    <th class="py-3 px-6 font-semibold">Status</th>
                    <th class="py-3 px-6 font-semibold text-center">View</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#778873]/20">

                @for ($i = 0; $i < 10; $i++)
                <tr class="hover:bg-[#A1BC98]/20 transition">
                    <td class="py-4 px-6 font-medium text-[#3E3F29]">
                        Jan Tirzuh Santos
                    </td>
                    <td class="py-4 px-6 text-gray-700">
                        Wedding
                    </td>
                    <td class="py-4 px-6">
                        <span class="inline-block px-3 py-1 text-xs rounded-full
                                     bg-[#A1BC98] text-[#3E3F29] font-medium">
                            Pending
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <a href="{{ route('coordinator.bookings.show', 1) }}"
                        class="inline-block px-4 py-2 text-xs rounded-lg
                                bg-[#778873] text-white
                                hover:bg-[#3E3F29] transition">
                            View
                        </a>
                    </td>

                @endfor

            </tbody>
        </table>

    </div>

    <!-- PAGINATION -->
    <div class="py-6 flex justify-center">
        <nav class="flex items-center gap-2 text-sm">
            <button class="px-3 py-2 rounded bg-[#778873] text-white opacity-40" disabled>‹</button>
            <button class="px-4 py-2 rounded bg-[#3E3F29] text-white font-medium">1</button>
            <button class="px-4 py-2 rounded bg-[#A1BC98] text-[#3E3F29] hover:bg-[#778873] hover:text-white">2</button>
            <button class="px-4 py-2 rounded bg-[#A1BC98] text-[#3E3F29] hover:bg-[#778873] hover:text-white">3</button>
            <button class="px-3 py-2 rounded bg-[#778873] text-white">›</button>
        </nav>
    </div>

</div>
@endsection
