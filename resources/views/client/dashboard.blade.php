@extends('layouts.client')

@section('content')

<div class="mb-8">
    <h2 class="text-3xl font-bold text-[#3E3F29]">
        Good Morning, {{ $user->name }}!
    </h2>
    <p class="text-[#3E3F29]/70">
        Here’s an overview of your events and bookings.
    </p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

{{-- My Event Bookings Section --}}
<div class="lg:col-span-2 bg-white rounded-2xl shadow p-5">

    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-[#3E3F29]">My Event Bookings</h3>

        {{-- See All Link at the top --}}
        @if($bookings->count() > 3)
            <a href="{{ route('client.bookings.index') }}"
               class="text-sm text-[#778873] hover:underline">
                See All
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($bookings->take(3) as $booking)
            <div class="rounded-xl overflow-hidden shadow-sm bg-[#F6F8F5] group hover:-translate-y-1 transition-transform duration-300">

                {{-- Coordinator Avatar --}}
                <div class="h-24 bg-[#A1BC98] flex items-end justify-center">
                    @if($booking->coordinator && $booking->coordinator->user)
                        <img
                            src="{{ $booking->coordinator->user->profile_photo 
                                   ? asset('storage/' . $booking->coordinator->user->profile_photo)
                                   : 'https://ui-avatars.com/api/?name=' . urlencode($booking->coordinator->user->name) . '&background=F2F4F1&color=3E3F29' }}"
                            class="w-16 h-16 rounded-full border-4 border-[#F6F8F5] shadow-md translate-y-8"
                            alt="{{ $booking->coordinator->user->name }}"
                        >
                    @endif
                </div>

                <div class="pt-10 pb-5 px-3 text-center">
                    {{-- Event Name --}}
                    <h4 class="text-sm font-bold text-[#3E3F29] mb-1">
                        {{ $booking->event_name }}
                    </h4>

                    {{-- Coordinator Name --}}
                    <span class="inline-block px-2 py-0.5 rounded-md bg-[#3E3F29]/10 text-[#3E3F29]
                                 text-[10px] font-bold uppercase tracking-wider mb-2">
                        Coordinator: {{ $booking->coordinator && $booking->coordinator->user ? $booking->coordinator->user->name : 'N/A' }}
                    </span>

                    {{-- Booking Date --}}
                    <div class="text-xs text-[#778873] font-medium flex items-center justify-center gap-1 mt-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Booked: {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</span>
                    </div>

                    {{-- Status Badge --}}
                    <span class="mt-2 inline-block px-2 py-0.5 rounded-md bg-[#3E3F29] text-white text-[10px] font-bold uppercase tracking-wider">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-sm font-semibold text-[#3E3F29]">
                    No event bookings yet
                </p>
            </div>
        @endforelse
    </div>

</div>

    {{-- Stats Section --}}
    <div class="space-y-4">
        @foreach($stats as $stat)
            @php
                $icon = match($stat['label'] ?? '') {
                    'My Bookings' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                    'Upcoming Events' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'Completed Events' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
                    default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'
                };
            @endphp

            <a href="{{ $stat['link'] ?? '#' }}" class="block">
                <div class="flex items-center gap-4
                            bg-gradient-to-r from-[#778873] to-[#3E3F29]
                            rounded-xl text-white p-5 shadow
                            hover:opacity-90 transition">

                    <div class="w-12 h-12 flex items-center justify-center
                                rounded-lg bg-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $icon !!}
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm opacity-90">{{ $stat['label'] ?? '' }}</p>
                        <h3 class="text-2xl font-bold">{{ $stat['value'] ?? 0 }}</h3>
                    </div>

                </div>
            </a>
        @endforeach
    </div>

</div>

{{-- Recommended Coordinators --}}
<div class="mb-10">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-[#3E3F29]">
            Recommended Coordinators
        </h3>
        <a href="{{ route('client.coordinators') }}" class="text-sm font-semibold text-[#556652] hover:text-[#3E3F29] hover:underline transition-colors">
            View All Coordinators &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($coordinators as $coord)
            <div class="bg-white rounded-2xl shadow-lg border border-[#E5E7EB]
                        hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 overflow-hidden">

                {{-- Avatar & Specialty --}}
                <div class="relative h-28 bg-gradient-to-br from-[#A1BC98] to-[#778873] flex items-end justify-center">
                    <div class="absolute -bottom-8 w-16 h-16 rounded-full border-4 border-white shadow-lg flex items-center justify-center bg-[#F6F8F5] text-[#3E3F29] font-bold text-xl">
                        {{ substr($coord->name, 0, 1) }}
                    </div>
                </div>

                <div class="pt-10 pb-5 px-5 text-center">
                    {{-- Name --}}
                    <h4 class="text-lg font-bold text-[#3E3F29] mb-1">
                        {{ $coord->name }}
                    </h4>

                    {{-- Specialty --}}
                    <span class="inline-block px-3 py-1 rounded-full bg-[#E8EFE5] text-[#556652] text-xs font-semibold mb-3">
                        {{ $coord->specialty ?? 'General' }}
                    </span>

                    {{-- Rating --}}
                    <div class="flex items-center justify-center gap-1 mb-4">
                        @php $rating = round($coord->average_rating ?? 0); @endphp
                        @for($i=0; $i<5; $i++)
                            <svg class="w-4 h-4 {{ $i < $rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="text-xs text-gray-500 ml-2">
                            ({{ $coord->reviews_count ?? 0 }} reviews)
                        </span>
                    </div>

                    {{-- View Profile Button --}}
                    <a href="{{ route('client.coordinators.view', $coord->id) }}"
                       class="w-full py-2 rounded-lg bg-gradient-to-r from-[#778873] to-[#3E3F29]
                              text-white font-bold text-sm hover:opacity-90 transition-all flex justify-center">
                        View Profile
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
@endpush
