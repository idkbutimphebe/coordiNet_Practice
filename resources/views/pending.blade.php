@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
            Pending Coordinators
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            Review and manage pending coordinator requests.
        </p>
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
                placeholder="Search pending coordinators..."
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

    <!-- CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        @php
            $coordinators = [
                ['name'=>'Jan Tirzuh Santos','email'=>'jan@example.com','plan'=>'Basic'],
                ['name'=>'Maria Clara','email'=>'maria@example.com','plan'=>'Premium'],
                ['name'=>'John Doe','email'=>'john@example.com','plan'=>'Basic'],
                ['name'=>'Anna Smith','email'=>'anna@example.com','plan'=>'Premium'],
                ['name'=>'Anna Smith','email'=>'anna@example.com','plan'=>'Premium'],
                ['name'=>'Anna Smith','email'=>'anna@example.com','plan'=>'Premium'],
            ];
        @endphp

        @foreach($coordinators as $coord)
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-[#E5E2DC] flex flex-col justify-between">
            
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-[#3E3F29]">{{ $coord['name'] }}</h2>
                <p class="text-sm text-gray-500">{{ $coord['email'] }}</p>
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium
                    @if($coord['plan'] === 'Premium')
                        bg-[#3E3F29] text-white
                    @else
                        bg-[#A1BC98] text-[#3E3F29]
                    @endif
                ">
                    {{ $coord['plan'] }}
                </span>
            </div>

            <div class="flex gap-2 mt-auto">
                <button class="flex-1 px-4 py-2 rounded-lg bg-[#A1BC98] text-[#3E3F29] font-medium hover:bg-[#778873] transition">
                    Approve
                </button>
                <button class="flex-1 px-4 py-2 rounded-lg bg-[#E9F0E6] text-[#3E3F29] font-medium hover:bg-[#778873] transition">
                    Decline
                </button>
            </div>

        </div>
        @endforeach

    </div>

    <!-- PAGINATION -->
    <div class="mt-6 py-4 flex justify-center">
        <nav class="flex items-center gap-2 text-sm">

            <button disabled
                class="px-2.5 py-1.5 rounded-md bg-[#778873] text-white opacity-40">
                ‹
            </button>

            <button class="px-3 py-1.5 rounded-md bg-[#3E3F29] text-white font-medium">
                1
            </button>

            <button class="px-3 py-1.5 rounded-md bg-[#A1BC98] text-[#3E3F29] hover:bg-[#778873] hover:text-white transition">
                2
            </button>

            <button class="px-2.5 py-1.5 rounded-md bg-[#778873] text-white hover:bg-[#3E3F29] transition">
                ›
            </button>

        </nav>
    </div>

</div>
@endsection
