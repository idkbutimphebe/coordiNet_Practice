@extends('layouts.dashboard')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-[#3E3F29]">Admin Dashboard</h2>
    <p class="text-[#3E3F29]/70">Overview of system activity and reports.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    @foreach($stats ?? [] as $stat)
    <a href="{{ $stat['route'] ?? '#' }}" class="bg-gradient-to-r from-[#778873] to-[#3E3F29] rounded-2xl text-white p-6 shadow-md flex items-center gap-4">
        <div class="flex-1">
            <p class="text-sm opacity-90">{{ $stat['label'] }}</p>
            <h3 class="text-2xl font-bold">{{ $stat['value'] }}</h3>
        </div>
    </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl shadow p-6">
    <h3 class="font-bold text-[#3E3F29] text-lg">Monthly Availability</h3>
    <div class="grid grid-cols-7 gap-2 text-center text-sm mt-4">
        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
            <div class="font-bold text-[#778873] pb-2">{{ $day }}</div>
        @endforeach

        @for($i = 1; $i <= now()->daysInMonth; $i++)
            <div class="p-3 rounded text-xs {{ isset($availability[$i]) && $availability[$i] === 'Booked' ? 'bg-[#3E3F29] text-white' : 'bg-[#F6F8F5] text-gray-400' }}">
                {{ $i }}
            </div>
        @endfor
    </div>
</div>
@endsection
