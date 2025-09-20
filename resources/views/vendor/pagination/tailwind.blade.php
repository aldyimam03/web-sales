@if ($paginator->hasPages())
    <div class="bg-white rounded-2xl shadow-xl p-6 mt-6 mx-9 fade-in">
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">

            <!-- Info (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:justify-between flex-1">
                <p class="text-sm text-slate-600 leading-5 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Menampilkan
                    @if ($paginator->firstItem())
                        <span class="font-semibold mx-1 text-slate-800">{{ $paginator->firstItem() }}</span>
                        hingga
                        <span class="font-semibold mx-1 text-slate-800">{{ $paginator->lastItem() }}</span>
                    @else
                        <span class="font-semibold mx-1 text-slate-800">{{ $paginator->count() }}</span>
                    @endif
                    dari
                    <span class="font-semibold mx-1 text-slate-800">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <!-- Pagination Links -->
            <div class="flex justify-end flex-1">
                <span class="relative z-0 inline-flex shadow-sm rounded-xl overflow-hidden">

                    {{-- Previous Page --}}
                    @if ($paginator->onFirstPage())
                        <span
                            class="px-3 py-2 text-sm text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed">
                            ‹
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                            class="px-3 py-2 text-sm text-slate-600 bg-white border border-slate-300 hover:bg-slate-50">
                            ‹
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="px-4 py-2 text-sm text-slate-400 bg-white border border-slate-300">...</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span
                                        class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 border border-blue-600">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-4 py-2 text-sm text-slate-700 bg-white border border-slate-300 hover:bg-blue-50 hover:text-blue-600">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                            class="px-3 py-2 text-sm text-slate-600 bg-white border border-slate-300 hover:bg-slate-50">
                            ›
                        </a>
                    @else
                        <span
                            class="px-3 py-2 text-sm text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed">
                            ›
                        </span>
                    @endif

                </span>
            </div>
        </nav>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endif
