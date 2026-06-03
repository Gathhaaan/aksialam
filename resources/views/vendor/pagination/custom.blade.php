@if ($paginator->hasPages())
    <nav class="flex justify-center" role="navigation" aria-label="Pagination Navigation">
        <ul class="inline-flex items-center bg-white border border-slate-200 rounded-md shadow-sm h-10 overflow-hidden">
            
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true">
                    <span class="flex items-center px-4 h-full text-sm font-semibold text-slate-400 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Previous
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center px-4 h-10 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Previous
                    </a>
                </li>
            @endif

            <li class="h-6 w-px bg-slate-200 mx-1"></li>

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span class="flex items-center justify-center px-3 h-10 text-sm font-medium text-slate-500 cursor-default">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page">
                                <span class="flex items-center justify-center w-10 h-10 border-x border-slate-800 font-bold text-slate-900 bg-white">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="flex items-center justify-center w-10 h-10 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            <li class="h-6 w-px bg-slate-200 mx-1"></li>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex items-center px-4 h-10 text-sm font-semibold text-slate-800 hover:text-slate-900 transition-colors">
                        Next
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </li>
            @else
                <li aria-disabled="true">
                    <span class="flex items-center px-4 h-10 text-sm font-semibold text-slate-400 cursor-not-allowed">
                        Next
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                </li>
            @endif
            
        </ul>
    </nav>
@endif
