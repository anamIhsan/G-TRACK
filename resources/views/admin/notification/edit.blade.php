@extends("layouts.app")

@section("title", "ADMIN | Edit Notifikasi")

@section("content")
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <form
            x-data="{
                jenisNotification:
                    '{{ old("jenis_notification", $data->jenis_notification) }}',
            }"
            action="{{ route("admin.notifications.update", $data->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="col-span-12"
        >
            @csrf
            @method("PUT")

            <div class="flex flex-col gap-2 space-y-5 md:flex-row sm:space-y-6">
                <div class="w-full bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Edit Notifikasi</h3>
                    </div>

                    <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                        {{-- Judul --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Judul
                            </label>
                            <input
                                name="judul"
                                type="text"
                                value="{{ old("judul", $data->judul) }}"
                                placeholder="Cth: Kehadiran"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            />
                        </div>

                        {{-- Pesan --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Pesan
                            </label>
                            <textarea
                                name="message"
                                rows="4"
                                placeholder="Cth: Mengharapkan kehadiran pada acara..."
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            >
{{ old("message", $data->message) }}</textarea
                            >
                        </div>

                        {{-- Jenis Notifikasi --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Jenis Notifikasi
                            </label>
                            <div class="relative z-20 bg-transparent">
                                <select
                                    name="jenis_notification"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    @change="jenisNotification = $event.target.value"
                                >
                                    <option
                                        value="SEKARANG"
                                        {{ old("jenis_notification", $data->jenis_notification) == "SEKARANG" ? "selected" : "" }}
                                    >
                                        KIRIM SEKARANG
                                    </option>
                                    <option
                                        value="TERJADWAL"
                                        {{ old("jenis_notification", $data->jenis_notification) == "TERJADWAL" ? "selected" : "" }}
                                    >
                                        KIRIM TERJADWAL
                                    </option>
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
                                            stroke-width="1.5"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        {{-- Jika jenis TERJADWAL --}}
                        <template x-if="jenisNotification === 'TERJADWAL'">
                            <div class="flex flex-col gap-3">
                                {{-- Tanggal --}}
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Tanggal
                                    </label>
                                    <input
                                        name="tanggal"
                                        type="date"
                                        value="{{ old("tanggal", $data->tanggal) }}"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    />
                                </div>

                                {{-- Jam --}}
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Jam
                                    </label>
                                    <input
                                        name="jam"
                                        type="time"
                                        value="{{ old("jam", $data->jam) }}"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    />
                                </div>

                                {{-- Jenis Pengiriman --}}
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Jenis Pengiriman
                                    </label>
                                    <select
                                        name="jenis_pengiriman"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    >
                                        <option
                                            value="SEKALI"
                                            {{ old("jenis_pengiriman", $data->jenis_pengiriman) == "SEKALI" ? "selected" : "" }}
                                        >
                                            SEKALI
                                        </option>
                                        <option
                                            value="TIAP_HARI"
                                            {{ old("jenis_pengiriman", $data->jenis_pengiriman) == "TIAP_HARI" ? "selected" : "" }}
                                        >
                                            TIAP HARI
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </template>

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
                    href="{{ route("admin.notifications.index") }}"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
                >
                    Kirim ulang
                    <i class="fa-regular fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
@endsection
