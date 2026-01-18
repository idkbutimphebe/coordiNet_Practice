    <!-- MY SCHEDULE -->
    <div class="space-y-4">

        <h2 class="text-lg font-semibold text-[#3E3F29]">
            My Schedule
        </h2>

        <div class="space-y-3">

            @php
                $schedule = [
                    ['Apr 18','10:00 AM','Wedding','Maria Lopez'],
                    ['May 02','2:00 PM','Birthday','John Reyes'],
                    ['May 21','9:00 AM','Wedding','Anna Cruz'],
                ];
            @endphp

            @foreach($schedule as [$date,$time,$event,$client])
            <div class="bg-white rounded-xl p-4
                        border border-[#A1BC98]/40
                        flex items-center gap-5">

                <div class="text-center">
                    <p class="text-sm font-bold text-[#3E3F29]">{{ $date }}</p>
                    <p class="text-xs text-gray-500">{{ $time }}</p>
                </div>

                <div class="flex-1">
                    <p class="font-semibold text-[#3E3F29]">{{ $event }}</p>
                    <p class="text-xs text-gray-500">Client: {{ $client }}</p>
                </div>
            </div>
            @endforeach

        </div>
    </div>
