@props([
    "data",
])

@if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->total() > 0)
    <div
        class="flex flex-col items-center justify-between gap-4 px-4 py-4 border-t border-gray-100 dark:border-gray-800 md:flex-row"
    >
        <div class="flex items-center text-gray-500 text-theme-sm dark:text-gray-400">
            Menampilkan {{ $data->firstItem() ?? 0 }} sampai {{ $data->lastItem() ?? 0 }} dari {{ $data->total() }}
            data
        </div>

        <nav class="flex items-center -space-x-px">
            {{-- Previous Page Link --}}
            @if ($data->onFirstPage())
                <span
                    class="px-3 py-2 ml-0 leading-tight text-gray-400 bg-white border border-gray-200 rounded-l-lg cursor-not-allowed dark:bg-gray-900 dark:border-gray-700"
                >
                    <i class="text-xs fa-solid fa-chevron-left"></i>
                </span>
            @else
                <a
                    href="{{ $data->previousPageUrl() }}"
                    class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                >
                    <i class="text-xs fa-solid fa-chevron-left"></i>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($data->getUrlRange(max(1, $data->currentPage() - 2), min($data->lastPage(), $data->currentPage() + 2)) as $page => $url)
                <a
                    href="{{ $url }}"
                    class="px-4 py-2 leading-tight border {{ $page == $data->currentPage() ? "z-10 bg-blue-600 border-blue-600 text-white" : "text-gray-500 bg-white border-gray-200 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white" }}"
                >
                    {{ $page }}
                </a>
            @endforeach

            {{-- Next Page Link --}}

            @if ($data->hasMorePages())
                <a
                    href="{{ $data->nextPageUrl() }}"
                    class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-200 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                >
                    <i class="text-xs fa-solid fa-chevron-right"></i>
                </a>
            @else
                <span
                    class="px-3 py-2 leading-tight text-gray-400 bg-white border border-gray-200 rounded-r-lg cursor-not-allowed dark:bg-gray-900 dark:border-gray-700"
                >
                    <i class="text-xs fa-solid fa-chevron-right"></i>
                </span>
            @endif
        </nav>
    </div>
@endif
