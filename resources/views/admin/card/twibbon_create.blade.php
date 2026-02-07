@extends("layouts.app")

@section("title", "ADMIN | Ubah Twibbon")

@section("content")
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <form
            action="{{ route("admin.cards.twibbon.store") }}"
            method="POST"
            enctype="multipart/form-data"
            class="col-span-12"
        >
            @csrf
            @method("POST")
            <div class="flex flex-col-reverse gap-2 space-y-5 md:flex-row sm:space-y-6">
                <div
                    class="w-full bg-white border border-gray-200 md:w-9/12 rounded-2xl dark:border-gray-800 dark:bg-white/5"
                >
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Ubah Twibbon</h3>
                    </div>
                    <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Upload gambar Twibbon
                            </label>
                            <input
                                name="twibbon"
                                type="file"
                                class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400"
                            />
                        </div>
                        {{--
                            @if (Auth::user()->role === "MASTER")
                            <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Daerah (Pilih jika ingin ubah daerah tertentu)
                            </label>
                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                            <select
                            name="zone_id"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                            @change="isOptionSelected = true"
                            >
                            <option
                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400"
                            disabled
                            selected
                            >
                            -- Pilih Salah Satu --
                            </option>
                            @foreach ($zones as $zone)
                            <option
                            {{ old("zone_id") === $zone->id ? "selected" : "" }}
                            value="{{ $zone->id }}"
                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400"
                            >
                            {{ $zone->nama }}
                            </option>
                            @endforeach
                            </select>
                            <span
                            class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-4 dark:text-gray-400"
                            >
                            <svg
                            class="stroke-current"
                            width="20"
                            height="20"
                            viewBox="0 0 20 20"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            >
                            <path
                            d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                            stroke=""
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            />
                            </svg>
                            </span>
                            </div>
                            </div>
                            @endif
                        --}}
                    </div>
                </div>
                <div
                    class="w-full col-span-4 bg-white border border-gray-200 md:w-1/3 rounded-2xl dark:border-gray-800 dark:bg-white/5 h-fit"
                >
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Ketentuan</h3>
                    </div>
                    <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                        <ul class="text-sm text-gray-500 list-disc list-inside dark:text-gray-400">
                            <li>Rasio gambar harus: 638x1008</li>
                        </ul>
                        <div class="flex flex-wrap items-center gap-6 sm:gap-8"></div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-4">
                <a
                    href="{{ route("admin.cards.index") }}"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Batal
                </a>

                <button
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
