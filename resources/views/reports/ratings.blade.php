@extends('layouts.dashboard')

@section('content')

<div class="p-6 space-y-10 bg-[#F6F8F5] min-h-screen">

    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29] tracking-tight">
                Ratings & Feedback
            </h1>
            <p class="text-sm text-[#6B705C] mt-1">
                What clients say about their coordinators
            </p>
        </div>

        <a href="{{ route('reports') }}"
           class="px-4 py-2 text-sm rounded-lg
                  border border-[#A1BC98]
                  text-[#3E3F29]
                  hover:bg-[#E3EAD7] transition">
            ← Back
        </a>
    </div>

    <!-- ================= RATINGS CARDS ================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        @php
            $ratings = [
                ['Jan Tirzuh Santos','Juan Dela Cruz',5,'Everything was perfect 💕'],
                ['Maria Lopez','April Martinez',4,'Very smooth coordination 🌸'],
                ['John Reyes','Mark Kevin',5,'Stress-free and organized ⭐'],
                ['Anna Cruz','Lara Santos',3,'Good but can improve 😊'],
                ['Kevin Ramos','Ryan Torres',4,'Professional and friendly 👍'],
            ];
        @endphp

        @foreach($ratings as [$client, $coordinator, $stars, $feedback])
        <div class="group relative rounded-3xl p-6
                    bg-gradient-to-br from-[#778873] to-[#3E3F29]
                    text-white shadow-lg
                    hover:-translate-y-1 hover:shadow-2xl
                    transition-all duration-300">

            <!-- glow -->
            <div class="absolute inset-0 bg-white/10 opacity-0
                        group-hover:opacity-100 rounded-3xl transition"></div>

            <div class="relative space-y-4">

                <!-- CLIENT NAME -->
                <div>
                    <h3 class="text-lg font-bold">
                        {{ $client }}
                    </h3>
                    <p class="text-xs text-white/80">
                        Coordinator: {{ $coordinator }}
                    </p>
                </div>

                <!-- STARS -->
                <div class="flex items-center gap-1 text-yellow-300">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $stars ? 'opacity-100' : 'opacity-30' }}"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955h4.157
                                     c.969 0 1.371 1.24.588 1.81l-3.363 2.443
                                     1.287 3.955c.3.921-.755 1.688-1.54 1.118
                                     L10 13.347l-3.366 2.461c-.784.57-1.838-.197-1.539-1.118
                                     l1.287-3.955-3.363-2.443c-.784-.57-.38-1.81.588-1.81h4.157l1.286-3.955z"/>
                        </svg>
                    @endfor
                </div>

                <!-- FEEDBACK -->
                <p class="text-sm text-white/90 italic">
                    “{{ $feedback }}”
                </p>

            </div>
        </div>
        @endforeach

    </div>

    <!-- ================= PAGINATION ================= -->
    <div class="pt-8">
        <div class="flex justify-center">
            <nav class="flex items-center gap-2 text-sm">

                <button class="px-3 py-1.5 rounded-md bg-[#A1BC98]
                               text-[#3E3F29] opacity-40 cursor-not-allowed">
                    ‹
                </button>

                <button class="px-3 py-1.5 rounded-md bg-[#3E3F29] text-white">
                    1
                </button>

                <button class="px-3 py-1.5 rounded-md bg-[#A1BC98]
                               text-[#3E3F29] hover:bg-[#778873]
                               hover:text-white transition">
                    2
                </button>

                <button class="px-3 py-1.5 rounded-md bg-[#A1BC98]
                               text-[#3E3F29] hover:bg-[#778873]
                               hover:text-white transition">
                    ›
                </button>

            </nav>
        </div>
    </div>

</div>

@endsection
