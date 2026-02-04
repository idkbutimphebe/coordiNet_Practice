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
    <form method="GET" class="flex items-center gap-3">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#3E3F29]/60">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-4.35-4.35m1.85-5.4a7.25 7.25 0 11-14.5 0 7.25 7.25 0 0114.5 0z"/>
                </svg>
            </span>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
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
            type="submit"
            class="px-6 py-3 rounded-lg
                   bg-[#3E3F29] text-white
                   text-sm font-semibold
                   hover:opacity-90 transition">
            Search
        </button>
    </form>

    <!-- CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        @forelse($pendingCoordinators as $coordinator)
        <div class="border p-4 rounded-lg bg-white shadow">
            <h2 class="font-semibold text-lg">{{ $coordinator->name }}</h2>
            <p class="text-sm text-gray-600">{{ $coordinator->email }}</p>
            <div class="mt-4 flex gap-2">
                <form action="{{ route('approve', $coordinator->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
                <form action="{{ route('decline', $coordinator->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Decline</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 flex flex-col items-center justify-center mt-10 text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-[#A1BC98]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <p class="text-center text-lg">No Pending Coordinators.</p>
        </div>
        @endforelse

    </div>

    <!-- PAGINATION -->
    <div class="mt-6 py-4 flex justify-center">
        {{ $pendingCoordinators->links() }}
    </div>

</div>
@endsection
