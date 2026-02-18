@extends('layouts.app')

@section('title', 'ADMIN | Edit Kegiatan')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <form x-data="sectionForm()" action="{{ route('admin.activities.update', $data->id) }}" method="POST"
            enctype="multipart/form-data" class="col-span-12">
            @csrf
            @method('PUT')
            <div class="flex flex-col gap-2 space-y-5 md:flex-row sm:space-y-6">
                <div class="w-full bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Edit Kegiatan</h3>
                    </div>

                    <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                        {{-- Nama Kegiatan --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Kegiatan
                            </label>
                            <input name="nama" type="text" value="{{ old('nama', $data->nama) }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                No whatsapp PJ
                            </label>
                            <input name="no_pj" id="no_pj" type="text" value="{{ old('nama', $data->no_pj) }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Tipe Kegiatan
                            </label>
                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select name="type"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                    @change="isOptionSelected = true; type = $event.target.value">
                                    <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled selected>
                                        -- Pilih Salah Satu --
                                    </option>
                                    <option {{ old('type', $data->type) === 'SEKALI' ? 'selected' : '' }} value="SEKALI"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Sekali
                                    </option>
                                    <option {{ old('type', $data->type) === 'TERJADWAL' ? 'selected' : '' }}
                                        value="TERJADWAL" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Terjadwal
                                    </option>
                                </select>
                                <span
                                    class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-4 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div x-transition x-show="type === 'SEKALI'">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Tanggal
                            </label>
                            <div class="relative">
                                <input name="tanggal" type="date" value="{{ old('tanggal', $data->tanggal) }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    onclick="this.showPicker()" />
                                <span
                                    class="absolute text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400">
                                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415Z" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div x-transition x-show="type === 'TERJADWAL'">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Hari
                            </label>
                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select name="hari"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                    @change="isOptionSelected = true">
                                    <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled selected>
                                        -- Pilih Salah Satu --
                                    </option>
                                    <option {{ old('hari', $data->hari) === 1 ? 'selected' : '' }} value="1"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Senin
                                    </option>
                                    <option {{ old('hari', $data->hari) === 2 ? 'selected' : '' }} value="2"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Selasa
                                    </option>
                                    <option {{ old('hari', $data->hari) === 3 ? 'selected' : '' }} value="3"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Rabu
                                    </option>
                                    <option {{ old('hari', $data->hari) === 4 ? 'selected' : '' }} value="4"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Kamis
                                    </option>
                                    <option {{ old('hari', $data->hari) === 5 ? 'selected' : '' }} value="5"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Jumat
                                    </option>
                                    <option {{ old('hari', $data->hari) === 6 ? 'selected' : '' }} value="6"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Sabtu
                                    </option>
                                    <option {{ old('hari', $data->hari) === 7 ? 'selected' : '' }} value="7"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Minggu
                                    </option>
                                </select>
                                <span
                                    class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-4 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        {{-- Jam --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jam</label>
                            <div class="relative">
                                <input name="jam" type="time" value="{{ old('jam', $data->jam) }}"
                                    onclick="this.showPicker()"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                <span class="absolute text-gray-500 -translate-y-1/2 top-1/2 right-3 dark:text-gray-400">
                                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.04175 9.99984C3.04175 6.15686 6.1571 3.0415 10.0001 3.0415C13.8431 3.0415 16.9584 6.15686 16.9584 9.99984C16.9584 13.8428 13.8431 16.9582 10.0001 16.9582C6.1571 16.9582 3.04175 13.8428 3.04175 9.99984ZM9.99998 10.7498C9.58577 10.7498 9.24998 10.4141 9.24998 9.99984V5.4165C9.24998 5.00229 9.58577 4.6665 9.99998 4.6665C10.4142 4.6665 10.75 5.00229 10.75 5.4165V9.24984H13.3334C13.7476 9.24984 14.0834 9.58562 14.0834 9.99984C14.0834 10.4141 13.7476 10.7498 13.3334 10.7498H9.99998Z" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        {{-- Materi --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Materi
                            </label>
                            <input name="materi" type="text" value="{{ old('materi', $data->materi) }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        {{-- Tempat --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Tempat
                            </label>
                            <input name="tempat" type="text" value="{{ old('tempat', $data->tempat) }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        @if (Auth::user()->role === 'MASTER')
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Daerah
                                </label>
                                <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                    <select name="zone_id"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" x-model="zone_id"
                                        @change="isOptionSelected = true">
                                        <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled
                                            x-selected="!zone_id">
                                            -- Pilih Salah Satu --
                                        </option>
                                        @foreach ($zones as $zone)
                                            <option {{ old('zone_id', $data->zone_id) === $zone->id ? 'selected' : '' }}
                                                value="{{ $zone->id }}"
                                                class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                {{ $zone->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span
                                        class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-4 dark:text-gray-400">
                                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div>
                            <div class="px-5 py-4 sm:px-6 sm:py-5">
                                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Status Kawin</h3>
                            </div>
                            <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                                <div class="flex flex-wrap items-center gap-6 sm:gap-8">
                                    <div>
                                        <label for="togel{{ 'BELUM' }}"
                                            class="flex items-center gap-3 text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
                                            <div class="relative">
                                                <input name="for_status_kawin[]" value="{{ 'BELUM' }}"
                                                    type="checkbox" id="togel{{ 'BELUM' }}" class="sr-only"
                                                    {{ in_array('BELUM', json_decode($data->for_status_kawin) ?? []) ? 'checked' : '' }}
                                                    @change="switcherToggleBelumMenikah = !switcherToggleBelumMenikah; "
                                                    {{ 'BELUM' === old('for_status_kawin') ? 'checked' : '' }} />

                                                <div :class="switcherToggleBelumMenikah ? 'bg-brand-500 dark:bg-brand-600' :
                                                    'bg-gray-300 dark:bg-white/10'"
                                                    class="block h-6 transition-colors duration-300 ease-in-out rounded-full w-11">
                                                </div>

                                                <div :class="switcherToggleBelumMenikah ? 'translate-x-full' : 'translate-x-0'"
                                                    class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow-theme-sm transition-transform duration-300 ease-linear">
                                                </div>
                                            </div>

                                            Belum menikah
                                        </label>
                                    </div>
                                    <div>
                                        <label for="togel{{ 'SUDAH' }}"
                                            class="flex items-center gap-3 text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
                                            <div class="relative">
                                                <input name="for_status_kawin[]" value="{{ 'SUDAH' }}"
                                                    type="checkbox" id="togel{{ 'SUDAH' }}" class="sr-only"
                                                    {{ in_array('SUDAH', json_decode($data->for_status_kawin) ?? []) ? 'checked' : '' }}
                                                    @change="switcherToggleSudahMenikah = !switcherToggleSudahMenikah"
                                                    {{ 'SUDAH' === old('for_status_kawin') ? 'checked' : '' }} />

                                                <div :class="switcherToggleSudahMenikah ? 'bg-brand-500 dark:bg-brand-600' :
                                                    'bg-gray-300 dark:bg-white/10'"
                                                    class="block h-6 transition-colors duration-300 ease-in-out rounded-full w-11">
                                                </div>

                                                <div :class="switcherToggleSudahMenikah ? 'translate-x-full' : 'translate-x-0'"
                                                    class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow-theme-sm transition-transform duration-300 ease-linear">
                                                </div>
                                            </div>

                                            Sudah menikah
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kategori Umur --}}
                        <div x-show="switcherToggleBelumMenikah" x-transition>
                            <div class="px-5 py-4 sm:px-6 sm:py-5">
                                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Kategori umur</h3>
                            </div>
                            <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                                <div class="flex flex-wrap items-center gap-6 sm:gap-8">
                                    @forelse ($age_categories as $age_category)
                                        <div x-data="{
                                            switcherToggle: {{ in_array($age_category->id, old('age_category_ids', $data->ageCategories->pluck('id')->toArray())) ? 'true' : 'false' }},
                                        }">
                                            <label for="togel{{ $age_category->id }}"
                                                class="flex items-center gap-3 text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
                                                <div class="relative">
                                                    <input name="age_category_ids[]" value="{{ $age_category->id }}"
                                                        type="checkbox" id="togel{{ $age_category->id }}"
                                                        class="sr-only" @change="switcherToggle = !switcherToggle"
                                                        {{ in_array($age_category->id, old('age_category_ids', $data->ageCategories->pluck('id')->toArray())) ? 'checked' : '' }} />

                                                    <!-- Track (warna latar belakang toggle) -->
                                                    <div :class="switcherToggle ? 'bg-brand-500 dark:bg-brand-600' :
                                                        'bg-gray-300 dark:bg-white/10'"
                                                        class="block h-6 transition-colors duration-300 ease-in-out rounded-full w-11">
                                                    </div>

                                                    <!-- Knob (bulatan toggle) -->
                                                    <div :class="switcherToggle ? 'translate-x-full' : 'translate-x-0'"
                                                        class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow-theme-sm transition-transform duration-300 ease-linear">
                                                    </div>
                                                </div>

                                                {{ $age_category->nama }}
                                            </label>
                                        </div>
                                    @empty
                                        <h3 class="text-sm font-medium text-center text-gray-700 dark:text-gray-400">
                                            Data Kosong
                                        </h3>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-4">
                <a href="{{ route('admin.activities.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Batal
                </a>

                <button
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function sectionForm() {
            return {
                // ===== INIT VALUE (EDIT) =====
                type: '{{ old('type', $data->type) }}',

                zone_id: '{{ old('zone_id', $data->zone_id) }}',

                switcherToggleBelumMenikah: @json(in_array('BELUM', json_decode($data->for_status_kawin) ?? [])),
                switcherToggleSudahMenikah: @json(in_array('SUDAH', json_decode($data->for_status_kawin) ?? [])),
            };
        }
    </script>

    <script>
        function normalizePhone(value) {
            value = value.replace(/\D/g, '');

            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            }

            if (!value.startsWith('62')) {
                value = '62' + value;
            }

            return value;
        }

        const input = document.getElementById('no_pj');
        if (input) {
            // normalisasi saat load (edit) & aman untuk create
            input.value = normalizePhone(input.value);

            // realtime saat ngetik
            input.addEventListener('input', function() {
                this.value = normalizePhone(this.value);
            });

            // pastikan bersih sebelum submit
            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    input.value = normalizePhone(input.value);
                });
            }
        }
    </script>
@endpush
