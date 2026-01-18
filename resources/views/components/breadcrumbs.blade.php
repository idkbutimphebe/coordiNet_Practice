<nav class="mb-6 text-sm text-[#3E3F29]/70" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2">

        <!-- Dashboard -->
        <li>
            <a href="{{ route('dashboard') }}"
               class="hover:text-[#3E3F29] font-medium">
                Dashboard
            </a>
        </li>

        @foreach($links as $link)
            <li class="flex items-center space-x-2">
                <span>/</span>

                @if(isset($link['url']))
                    <a href="{{ $link['url'] }}"
                       class="hover:text-[#3E3F29] font-medium">
                        {{ $link['label'] }}
                    </a>
                @else
                    <span class="font-semibold text-[#3E3F29]">
                        {{ $link['label'] }}
                    </span>
                @endif
            </li>
        @endforeach

    </ol>
</nav>
