@extends('layouts.app')

@section('title', 'ADMIN | Edit Pengguna')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <form x-data="sectionForm()" action="{{ route('admin.users.update', $data->id) }}" method="POST"
            enctype="multipart/form-data" class="col-span-12">
            @csrf
            @method('PUT')
            <div class="flex flex-col gap-2 space-y-5 sm:space-y-6">
                <div class="w-full bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                            Edit Pengguna {{ $data->nama }}
                        </h3>
                    </div>
                    <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                        <div class="p-2 border border-gray-200 dark:border-white/10 rounded-2xl">
                            <div class="p-2">
                                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Foto profil</h3>
                            </div>
                            <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Upload gambar profile
                                    </label>
                                    <div id="writerProfile" class="mb-3">
                                        <img id="gambarProfileImg" src="" alt="Image to crop"
                                            style="max-width: 100%; display: none" />
                                    </div>
                                    <input name="gambarProfileView" type="file" id="inputImage" accept="image/*"
                                        class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400" />
                                    <input type="text" id="gambarProfile" name="gambar" class="hidden" />
                                </div>
                                <button type="button" id="btnCrop"
                                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                    <span id="btnText">Simpan Gambar</span>
                                    <div id="btnSpinner"
                                        class="hidden w-5 h-5 border-4 border-white rounded-full animate-spin border-t-transparent">
                                    </div>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama
                            </label>
                            <input value="{{ old('nama', $data->nama) }}" name="nama" type="text"
                                placeholder="Cth: Agus Hantono"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Email
                            </label>
                            <input value="{{ old('email', $data->email) }}" name="email" type="email"
                                placeholder="Cth: jarwo@mail.com"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Username
                            </label>
                            <input value="{{ old('username', $data->username) }}" name="username" type="text"
                                placeholder="Cth: agus hartono"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                NFC ID
                            </label>
                            <input value="{{ old('nfc_id', $data->nfc_id) }}" name="nfc_id" type="number"
                                placeholder="Cth: 1092319232"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Password
                            </label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <input :type="showPassword ? 'text' : 'password'"
                                    placeholder="Isi jika password ingin diganti" name="password"
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                <span @click="showPassword = !showPassword"
                                    class="absolute z-30 text-gray-500 -translate-y-1/2 cursor-pointer right-4 top-1/2 dark:text-gray-400">
                                    <!-- icon mata -->
                                    <svg x-transition x-show="!showPassword" class="fill-current" width="20"
                                        height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z"
                                            fill="#98A2B3" />
                                    </svg>
                                    <svg x-transition x-show="showPassword" class="fill-current" width="20"
                                        height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z"
                                            fill="#98A2B3" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Tanggal Lahir
                            </label>

                            <div class="relative">
                                <input name="tanggal_lahir" type="date" placeholder="Select date"
                                    value="{{ old('tanggal_lahir', $data->tanggal_lahir) }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    onclick="this.showPicker()" />
                                <span
                                    class="absolute text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400">
                                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                            fill="" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nomor Wa/Tlp (Diawali 62)
                            </label>
                            <input value="{{ old('no_tlp', $data->no_tlp) }}" name="no_tlp" id="no_tlp"
                                type="text" placeholder="Cth: 6289812328765"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Jenis Kelamin
                            </label>
                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select name="kelamin"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                    @change="isOptionSelected = true">
                                    <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled selected>
                                        -- Pilih Salah Satu --
                                    </option>
                                    <option {{ $data->kelamin === 'LK' ? 'selected' : '' }} value="LK"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Laki-Laki
                                    </option>
                                    <option {{ $data->kelamin === 'PR' ? 'selected' : '' }} value="PR"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Perempuan
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

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Status Menikah
                            </label>
                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select name="status_kawin"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                    @change="isOptionSelected = true">
                                    <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled selected>
                                        -- Pilih Salah Satu --
                                    </option>
                                    <option {{ $data->status_kawin === 'SUDAH' ? 'selected' : '' }} value="SUDAH"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Sudah Menikah
                                    </option>
                                    <option {{ $data->status_kawin === 'BELUM' ? 'selected' : '' }} value="BELUM"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Belum Menikah
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
                    </div>
                </div>
                <div class="w-full bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Informasi Daerah</h3>
                    </div>
                    <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                        @if (Auth::user()->role === 'MASTER')
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Daerah
                                </label>
                                <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                    <select name="zone_id"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" x-model="zone_id"
                                        @change=" fetchInterests(zone_id); getWorks(); fetchMetafields(); isOptionSelected = true">
                                        <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled
                                            selected>
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
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Minat
                            </label>

                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select name="interest_id" x-model="interestId" @change="fetchSubInterests()"
                                    :disabled="!zone_id"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'">
                                    <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled>
                                        -- Pilih Salah Satu --
                                    </option>
                                    <option selected value=""
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Tidak diatur
                                    </option>

                                    <template x-for="interest in interests" :key="interest.id">
                                        <option :value="interest.id" x-text="interest.nama"
                                            :selected="interest.id == interestId"></option>
                                    </template>
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

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Sub Minat
                            </label>

                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select name="sub_interest_id" x-model="subInterestId"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                    @change="isOptionSelected = true">
                                    <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled
                                        x-selected="!interestId">
                                        -- Pilih Salah Satu --
                                    </option>
                                    <option selected value=""
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Tidak diatur
                                    </option>

                                    <template x-for="sub in subInterests" :key="sub.id">
                                        <option :value="sub.id" x-text="sub.nama"
                                            :selected="sub.id == subInterestId"></option>
                                    </template>
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

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Pekerjaan
                            </label>

                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select name="work_id" x-model="work_id"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                    @change="isOptionSelected = true">
                                    <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled>
                                        -- Pilih Salah Satu --
                                    </option>
                                    <option selected value=""
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Tidak diatur
                                    </option>

                                    <template x-for="work in works" :key="work.id">
                                        <option :value="work.id" x-text="work.nama" :selected="work.id == work_id">
                                        </option>
                                    </template>
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
                    </div>

                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Metafield</h3>
                    </div>
                    <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                        <!-- LOADING -->
                        <template x-if="loadingMetafield">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Memuat field tambahan...</p>
                        </template>

                        <!-- METAFIELD -->
                        <template x-for="metafield in metafields" :key="metafield.id">
                            <!-- STRING -->
                            <template x-if="metafield.type === 'STRING'">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                                        x-text="metafield.field"></label>

                                    <input type="text" :name="`value[${metafield.id}]`" x-model="metafield.value"
                                        class="w-full px-4 border rounded-lg dark:bg-dark-900 h-11" />
                                </div>
                            </template>

                            <!-- ENUM -->
                            <template x-if="metafield.type !== 'STRING'">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                                        x-text="metafield.field"></label>

                                    <select :name="`value[${metafield.id}]`" x-model="metafield.value"
                                        class="w-full px-4 border rounded-lg dark:bg-dark-900 h-11">
                                        <option value="">-- Pilih Salah Satu --</option>

                                        <template x-for="opt in JSON.parse(metafield.enum_values)" :key="opt">
                                            <option :value="opt" x-text="opt"></option>
                                        </template>
                                    </select>
                                </div>
                            </template>
                        </template>

                        <!-- EMPTY -->
                        <template x-if="!loadingMetafield && metafields.length === 0">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada field tambahan</p>
                        </template>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-4">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Batal
                </a>

                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var image = document.getElementById('gambarProfileImg');
        var inputImage = document.getElementById('inputImage');
        var user = @json($data);
        var cropper;

        inputImage.addEventListener('change', function(e) {
            var reader = new FileReader();
            document.getElementById('writerProfile').style.display = 'block';

            reader.onload = function(event) {
                image.src = event.target.result;
                image.style.display = 'block';
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 0.5,
                });
            };
            reader.readAsDataURL(inputImage.files[0]);
        });

        document.getElementById('btnCrop').addEventListener('click', function(e) {
            e.preventDefault();

            try {
                document.getElementById('btnText').style.display = 'none';
                document.getElementById('btnSpinner').style.display = 'inline-block';

                var canvas = cropper.getCroppedCanvas();

                canvas.toBlob(function(blob) {
                    var formData = new FormData();
                    formData.append('image', blob);

                    fetch('{{ route('admin.users.image.crop') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData,
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            console.log(data);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Berhasil upload gambar',
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Masukan profile dan coba ulangi lagi',
                                });
                            }
                            document.getElementById('writerProfile').style.display = 'none';
                            document.getElementById('gambarProfile').value = data.gambar;

                            document.getElementById('btnText').style.display = 'inline';
                            document.getElementById('btnSpinner').style.display = 'none';
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Masukan profile dan coba ulangi lagi',
                            });

                            document.getElementById('btnText').style.display = 'inline';
                            document.getElementById('btnSpinner').style.display = 'none';
                        });
                });
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Masukan profile dan coba ulangi lagi',
                });

                document.getElementById('btnText').style.display = 'inline';
                document.getElementById('btnSpinner').style.display = 'none';
            }
        });
    </script>

    <script>
        function sectionForm() {
            return {
                user_id: '{{ $data->id }}',
                zone_id: '{{ old('zone_id', $data->zone_id) }}',

                interestId: '{{ old('interest_id', $data->interest_id) }}',
                subInterestId: '{{ old('sub_interest_id', $data->sub_interest_id) }}',
                interests: [],
                subInterests: [],

                work_id: '{{ old('work_id', $data->work_id) }}',
                works: [],

                metafields: [],
                loadingMetafield: false,

                async fetchMetafields() {
                    if (!this.zone_id || !this.user_id) {
                        this.metafields = [];
                        return;
                    }

                    this.loadingMetafield = true;

                    try {
                        const res = await fetch(`/api/get-metafields-by-zone/${this.zone_id}/${this.user_id}`);
                        this.metafields = await res.json();
                    } catch {
                        this.metafields = [];
                    } finally {
                        this.loadingMetafield = false;
                    }
                },

                async fetchInterests(zone_id) {
                    if (!zone_id) return;
                    const res = await fetch(`/api/get-interests-by-zone/${zone_id}`);
                    this.interests = await res.json();
                    if (this.interestId) await this.fetchSubInterests();
                },

                async fetchSubInterests() {
                    if (!this.interestId) {
                        this.subInterests = [];
                        return;
                    }
                    const res = await fetch(`/api/get-sub_interest/${this.interestId}`);
                    this.subInterests = await res.json();
                },

                async getWorks() {
                    if (!this.zone_id) return;
                    const res = await fetch(`/api/get-works/${this.zone_id}`);
                    this.works = await res.json();
                },

                async init() {
                    if (this.zone_id) {
                        await this.fetchInterests(this.zone_id);
                        await this.getWorks();
                        await this.fetchMetafields();
                    }
                },
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

        const input = document.getElementById('no_tlp');
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
