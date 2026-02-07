@extends("layouts.app")

@section("title", "ADMIN | Ubah Materi Tingkat Kehataman")

@section("content")
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <form action="{{ route("admin.metafield_level.update", $data->id) }}" method="POST" class="col-span-12">
            @csrf
            @method("PUT")
            <div class="flex">
                <div class="w-full bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                            Ubah Materi Tingkat Kehataman
                        </h3>
                    </div>
                    <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Materi Tingkat Kehataman
                            </label>
                            <input
                                name="field_name"
                                type="text"
                                value="{{ old("field_name", $data->field_name) }}"
                                placeholder="Cth: Quran"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Jumlah Halaman
                            </label>
                            <input
                                name="halaman"
                                type="number"
                                value="{{ old("halaman", $data->halaman) }}"
                                placeholder="Cth: 954"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            />
                        </div>

                        @if (Auth::user()->role === "MASTER")
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Daerah
                                </label>
                                <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                    <select
                                        name="zone_id"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                        x-model="zone_id"
                                        @change="isOptionSelected = true"
                                    >
                                        <option
                                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400"
                                            disabled
                                            x-selected="!zone_id"
                                        >
                                            -- Pilih Salah Satu --
                                        </option>
                                        @foreach ($zones as $zone)
                                            <option
                                                {{ $zone->id == old("zone_id") ? "selected" : "" }}
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
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-4">
                <a
                    href="{{ route("admin.metafield_level.index") }}"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
