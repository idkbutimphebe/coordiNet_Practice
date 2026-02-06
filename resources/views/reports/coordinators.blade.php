@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-8">

    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between no-print">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
                All Coordinators
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Complete list of registered coordinators
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 text-sm rounded-lg
                      border border-[#A1BC98]
                      text-[#3E3F29]
                      hover:bg-[#E3EAD7] transition">
                ← Back
            </a>

            <button onclick="window.print()" 
                    class="px-4 py-2 text-sm rounded-lg
                           bg-[#3E3F29] text-white
                           hover:bg-[#2c2d1f] transition">
                🖨️ Print
            </button>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="bg-white rounded-xl shadow-sm p-5 no-print">
        <form method="GET" action="{{ route('reports.coordinators') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Search Bar -->
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-[#3E3F29] mb-2">Search</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by coordinator name, email, or phone..."
                       class="w-full px-4 py-2 rounded-lg border border-[#A1BC98] 
                              focus:outline-none focus:ring-2 focus:ring-[#A1BC98]">
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-3 flex gap-3">
                <button type="submit" 
                        class="px-6 py-2 rounded-lg bg-[#A1BC98] text-[#3E3F29] 
                               font-medium hover:bg-[#8aa880] transition">
                    Apply Filter
                </button>
                <a href="{{ route('reports.coordinators') }}" 
                   class="px-6 py-2 rounded-lg border border-[#A1BC98] text-[#3E3F29] 
                          hover:bg-[#E3EAD7] transition">
                    Clear Filter
                </a>
            </div>
        </form>
    </div>

    <!-- ================= COORDINATORS TABLE ================= -->
    <div class="print-area bg-white rounded-2xl shadow-sm overflow-hidden">

        <table class="w-full text-sm text-left">
            <thead class="bg-[#A1BC98]/40 text-[#3E3F29]">
                <tr>
                    <th class="py-3 px-5 font-semibold">Coordinator Name</th>
                    <th class="py-3 px-5 font-semibold">Email</th>
                    <th class="py-3 px-5 font-semibold">Phone</th>
                    <th class="py-3 px-5 font-semibold text-center">Total Bookings</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#778873]/20">

                @forelse($coordinators as $coordinator)
                <tr class="hover:bg-[#A1BC98]/20 transition">
                    <td class="py-3 px-5 font-medium text-[#3E3F29]">
                        {{ $coordinator->coordinator_name }}
                    </td>

                    <td class="py-3 px-5 text-gray-700">
                        {{ $coordinator->user->email ?? '-' }}
                    </td>

                    <td class="py-3 px-5 text-gray-700">
                        {{ $coordinator->phone_number ?? '-' }}
                    </td>

                    <td class="py-3 px-5 text-center">
                        <span class="inline-block px-3 py-1 text-xs rounded-full
                                     bg-[#A1BC98] text-[#3E3F29] font-medium">
                            {{ $coordinator->bookings_count ?? 0 }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 px-5 text-center text-gray-500">
                        No coordinators found.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

    </div>

</div>

<!-- ================= PRINT STYLES ================= -->
<style>
@media print {
    body * { visibility: hidden; }
    .print-area, .print-area * { visibility: visible; }
    .print-area { position: absolute; inset: 0; width: 100%; box-shadow: none !important; border-radius: 0 !important; }
    table { border-collapse: collapse; width: 100%; font-size: 12px; }
    th, td { border: 1px solid #A1BC98 !important; padding: 10px 14px !important; }
    th { background: #A1BC98 !important; color: #3E3F29 !important; font-weight: 700 !important; }
    tr:hover { background: transparent !important; }
}
</style>
@endsection