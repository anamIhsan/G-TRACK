@extends('layouts.app')

@section('title', 'ADMIN | Profile')

@section('content')
    <div x-data="{ isEdit: false }"
        class="p-5 bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5 lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Profile</h3>

        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex flex-col flex-wrap items-center w-full gap-6 xl:flex-row">
                    <div class="flex flex-col items-center w-full gap-6 xl:flex-row">
                        <form action="{{ route('user.profiles.update.photo') }}" method="POST" x-ref="imgForm"
                            enctype="multipart/form-data"
                            class="relative w-20 h-20 overflow-hidden border border-gray-200 rounded-full dark:border-gray-800 group">
                            @method('PUT')
                            @csrf
                            <img class="object-cover w-full h-full"
                                src="{{ $data->gambar ? $data->gambar : 'https://ui-avatars.com/api/?name=' . $data->nama . '&background=random' }}"
                                alt="user" />

                            <a @click="$refs.imgOpen.click()"
                                class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 opacity-0 cursor-pointer bg-black/50 group-hover:opacity-100"
                                title="Ubah Foto Profil">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                            </a>
                            <input name="gambar" x-ref="imgOpen" class="hidden" type="file"
                                @change="$refs.imgForm.submit()" />
                        </form>

                        <div class="order-3 xl:order-2">
                            <h4
                                class="mb-2 text-lg font-semibold text-center text-gray-800 dark:text-white/90 xl:text-left">
                                {{ $data->nama }}
                            </h4>
                            <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $data->username }}</p>
                                <div class="hidden h-3.5 w-px bg-gray-300 dark:bg-gray-700 xl:block"></div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $data->kelamin === 'LK' ? 'Laki-laki' : ($data->kelamin === 'PR' ? 'Perempuan' : 'Tidak diatur') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <a x-show="!isEdit" href="{{ route('user.profiles.download.card') }}"
                    class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200 lg:inline-flex lg:w-auto">
                    <i class="fa-solid fa-download"></i>
                    Download Kartu
                </a>
                <button @click="isEdit = true"
                    class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200 lg:inline-flex lg:w-auto">
                    <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                            fill="" />
                    </svg>
                    Edit
                </button>
                <button x-show="isEdit" @click="isEdit = false"
                    class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full shadow-theme-xs hover:bg-red-50 hover:text-red-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-red/5 dark:hover:text-red-200 lg:inline-flex lg:w-auto">
                    <i class="fa-solid fa-xmark"></i>
                    Batal
                </button>
                <form id="delete-form-{{ $data->id }}" x-show="!isEdit"
                    action="{{ route('user.profiles.destroy', $data->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button @click="confirmDelete('delete-form-{{ $data->id }}')" type="button"
                        class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full shadow-theme-xs hover:bg-gray-50 hover:text-red-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-red/5 dark:hover:text-red-200 lg:inline-flex lg:w-auto">
                        <i class="fa-regular fa-trash-can"></i>
                        <p class="hidden text-theme-sm md:block">Hapus</p>
                    </button>
                </form>
            </div>
        </div>

        <form x-data="sectionForm()" action="{{ route('user.profiles.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">Data pribadi</h4>

                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Nama</p>
                                <p x-transition x-show="!isEdit"
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->nama ?? 'Tidak diatur' }}
                                </p>
                                <div x-transition x-show="isEdit">
                                    <input name="nama" value="{{ $data->nama }}" type="text"
                                        placeholder="Cth: Agus Hantono"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                </div>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Username</p>
                                <p x-transition x-show="!isEdit"
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->username ?? 'Tidak diatur' }}
                                </p>
                                <div x-transition x-show="isEdit">
                                    <input name="username" value="{{ $data->username }}" type="text"
                                        placeholder="Cth: agushartono"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                </div>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Email</p>
                                <p x-transition x-show="!isEdit"
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->email ?? 'Tidak diatur' }}
                                </p>
                                <div x-transition x-show="isEdit">
                                    <input name="email" value="{{ $data->email }}" type="email"
                                        placeholder="Cth: mail@mail.com"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                </div>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    {{ $data->status_kawin === 'BELUM' ? 'Kategori umur' : 'Status Menikah' }}
                                </p>
                                <p x-transition x-show="!isEdit"
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    @if ($data->status_kawin === 'BELUM')
                                        {{ $data->ageCategory?->nama ?? 'Tidak diatur' }}
                                    @else
                                        Sudah Menikah
                                    @endif
                                </p>
                                <div x-transition x-show="isEdit" x-data="{ isOptionSelected: false }"
                                    class="relative z-20 bg-transparent">
                                    <select name="status_kawin"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                        @change="isOptionSelected = true">
                                        <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled
                                            selected>
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

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Nomor WA/Tlp</p>
                                <p x-transition x-show="!isEdit"
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->no_tlp ?? 'Tidak diatur' }}
                                </p>
                                <div x-transition x-show="isEdit">
                                    <input name="no_tlp" id="no_tlp" value="{{ $data->no_tlp }}" type="text"
                                        placeholder="Cth: 6289812328765"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                </div>
                            </div>

                            <div x-transition x-show="!isEdit">
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">NFC ID</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->nfc_id ?? 'Tidak diatur' }}
                                </p>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Tanggal Lahir
                                </p>
                                <p x-transition x-show="!isEdit"
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->tanggal_lahir ?? 'Tidak diatur' }}
                                </p>
                                <div x-transition x-show="isEdit" class="relative">
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

                            <div x-transition x-show="!isEdit">
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Umur</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->umur ?? 'Tidak diatur' }}
                                    @if ($data->umur)
                                        Tahun
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Jenis Kelamin
                                </p>
                                <p x-transition x-show="!isEdit"
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->kelamin_format ?? 'Tidak diatur' }}
                                </p>
                                <div>
                                    <div x-transition x-show="isEdit" x-data="{ isOptionSelected: false }"
                                        class="relative z-20 bg-transparent">
                                        <select name="kelamin"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            :class="isOptionSelected && 'text-gray-800 dark:text-white/90'">
                                            <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled
                                                selected>
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
                                            <svg class="stroke-current" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Pekerjaan</p>
                                <p x-transition x-show="!isEdit"
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->work?->nama ?? 'Tidak diatur' }}
                                </p>
                                <div>
                                    <div x-transition x-show="isEdit" x-data="{ isOptionSelected: false }"
                                        class="relative z-20 bg-transparent">
                                        <select name="work_id"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            :class="isOptionSelected && 'text-gray-800 dark:text-white/90'">
                                            <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled
                                                selected>
                                                -- Pilih Salah Satu --
                                            </option>
                                            @foreach ($works as $work)
                                                <option
                                                    {{ old('work_id', $data->work_id) === $work->id ? 'selected' : '' }}
                                                    value="{{ $work->id }}"
                                                    class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                    {{ $work->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span
                                            class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-4 dark:text-gray-400">
                                            <svg class="stroke-current" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div x-transition x-show="!isEdit">
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Daerah</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->zone?->nama ?? 'Tidak diatur' }}
                                </p>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Minat</p>
                                <p x-transition x-show="!isEdit"
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->interest?->nama ?? 'Tidak diatur' }}
                                </p>
                                <div>
                                    <div x-transition x-show="isEdit" x-data="{ isOptionSelected: false }"
                                        class="relative z-20 bg-transparent">
                                        <select x-model="interestId"
                                            @change="fetchSubInterests(); isOptionSelected = true" name="interest_id"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            :class="isOptionSelected && 'text-gray-800 dark:text-white/90'">
                                            <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled
                                                selected>
                                                -- Pilih Salah Satu --
                                            </option>
                                            @foreach ($interests as $interest)
                                                <option
                                                    {{ old('interest_id', $data->interest_id) === $interest->id ? 'selected' : '' }}
                                                    value="{{ $interest->id }}"
                                                    class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                    {{ $interest->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span
                                            class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-4 dark:text-gray-400">
                                            <svg class="stroke-current" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Sub Minat</p>
                                <p x-transition x-show="!isEdit"
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $data->subInterest?->nama ?? 'Tidak diatur' }}
                                </p>
                                <div>
                                    <div x-transition x-show="isEdit" x-data="{ isOptionSelected: false }"
                                        class="relative z-20 bg-transparent">
                                        <select name="sub_interest_id" x-model="subInterestId"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            :class="isOptionSelected && 'text-gray-800 dark:text-white/90'">
                                            <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled
                                                :selected="!interestId">
                                                -- Pilih Salah Satu --
                                            </option>
                                            <option value=""
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
                                            <svg class="stroke-current" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @php
                                $baseMetafieldUsers = $data->baseMetafieldUsers;
                            @endphp

                            @foreach ($metafieldUsers as $metafield)
                                @if (isset($baseMetafieldUsers))
                                    @php
                                        $baseMetafieldUser = $baseMetafieldUsers
                                            ->where('metafield_user_id', $metafield->id)
                                            ->first();
                                    @endphp

                                    @if ($baseMetafieldUser)
                                        <div>
                                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                                {{ $baseMetafieldUser->metafieldUser->field }}
                                            </p>
                                            <p x-transition x-show="!isEdit"
                                                class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                {{ $baseMetafieldUser->value ?? 'Tidak diatur' }}
                                            </p>

                                            @if ($metafield->type === 'STRING')
                                                <div x-transition x-show="isEdit">
                                                    <input
                                                        value="{{ isset($baseMetafieldUser->value) ? $baseMetafieldUser->value : '' }}"
                                                        name="value[{{ $metafield->id }}]" type="text"
                                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                </div>
                                            @else
                                                <div x-transition x-show="isEdit">
                                                    <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                                        <select name="value[{{ $metafield->id }}]"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                                            @change="isOptionSelected = true">
                                                            <option
                                                                class="text-gray-700 dark:bg-gray-900 dark:text-gray-400"
                                                                disabled selected>
                                                                -- Pilih Salah Satu --
                                                            </option>
                                                            @foreach (json_decode($metafield->enum_values) as $value)
                                                                <option
                                                                    {{ isset($baseMetafieldUser->value) ? ($value === $baseMetafieldUser->value ? 'selected' : '') : '' }}
                                                                    value="{{ $value }}"
                                                                    class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span
                                                            class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-4 dark:text-gray-400">
                                                            <svg class="stroke-current" width="20" height="20"
                                                                viewBox="0 0 20 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                                                                    stroke="" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div>
                                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                                {{ $metafield->field }}
                                            </p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                Tidak diatur
                                            </p>
                                        </div>
                                    @endif
                                @else
                                    <div>
                                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                            {{ $metafield->field }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">Tidak diatur</p>
                                        <div x-transition x-show="isEdit">
                                            <input
                                                value="{{ isset($baseMetafieldUser->value) ? $baseMetafieldUser->value : '' }}"
                                                name="value[{{ $metafield->id }}]" type="text"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <button x-transition x-show="isEdit" type="submit"
                    class="flex items-center justify-center w-full gap-2 px-4 py-3 mt-6 text-sm font-medium text-blue-700 bg-white border border-blue-300 rounded-full shadow-theme-xs hover:bg-blue-50 hover:text-blue-800 dark:border-blue-700 dark:bg-blue-800 dark:text-blue-400 dark:hover:bg-blue/5 dark:hover:text-blue-200 lg:inline-flex lg:w-auto">
                    <i class="fa fa-save"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- alert success --}}
    <script>
        if ({{ session()->has('success') }}) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
            });
        }
    </script>

    {{-- alert confirm delete --}}
    <script>
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                html: `
            <div style="text-align:left">
                <label for="reason-select"
                    style="display:block; margin-bottom:6px; font-size:14px; font-weight:500; color:#374151;">
                    Pilih Alasan
                </label>

                <select id="reason-select"
                    style="
                        width:100%;
                        height:2.75rem;
                        border-radius:0.5rem;
                        border:1px solid #D1D5DB;
                        background-color:transparent;
                        padding:0.625rem 2.75rem 0.625rem 1rem;
                        font-size:0.875rem;
                        color:#1F2937;
                        appearance:none;
                        transition:all 0.2s;
                    ">
                    <option value="" disabled selected>-- Pilih Salah Satu --</option>
                    <option value="Mondok">Mondok</option>
                    <option value="Menikah">Menikah</option>
                    <option value="Pindah Sambung">Pindah Sambung</option>
                    <option value="Lainnya">Lainnya...</option>
                </select>

                <input id="custom-reason"
                    placeholder="Tulis alasan lain..."
                    style="
                        margin-top:0.75rem;
                        display:none;
                        width:100%;
                        height:2.75rem;
                        border-radius:0.5rem;
                        border:1px solid #D1D5DB;
                        padding:0.625rem 1rem;
                        font-size:0.875rem;
                        color:#1F2937;
                        background-color:transparent;
                        transition:all 0.2s;
                    " />
            </div>
        `,
                preConfirm: () => {
                    const select = document.getElementById('reason-select');
                    const custom = document.getElementById('custom-reason');

                    if (!select.value) {
                        Swal.showValidationMessage('Silakan pilih alasan!');
                        return false;
                    }

                    if (select.value === 'Lainnya' && !custom.value) {
                        Swal.showValidationMessage('Isi alasan lain!');
                        return false;
                    }

                    return select.value === 'Lainnya' ? custom.value : select.value;
                },
                didOpen: () => {
                    const select = document.getElementById('reason-select');
                    const custom = document.getElementById('custom-reason');

                    select.addEventListener('change', () => {
                        if (select.value === 'Lainnya') {
                            custom.style.display = 'block';
                            custom.focus();
                        } else {
                            custom.style.display = 'none';
                            custom.value = '';
                        }
                    });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const reason = result.value;

                    fetch(`/api/users/reason/${formId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                reason: reason,
                            }),
                        })
                        .then((res) => res.json())
                        .then((data) => {
                            console.log('Reason:', data.reason);
                        })
                        .catch((err) => {
                            console.error(err);
                        });

                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var image = document.getElementById('gambarProfileImg');
        var inputImage = document.getElementById('inputImage');
        var cropper;

        inputImage.addEventListener('change', function(e) {
            var reader = new FileReader();
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '{{ session('success') }}',
                        });
                        document.getElementById('writerProfile').style.display = 'none';
                        document.getElementById('gambarProfile').value = data.gambar;
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: '{{ session('error') }}',
                        });
                        document.getElementById('writerProfile').style.display = 'none';
                    });
            });
        });
    </script>

    <script>
        function sectionForm() {
            return {
                interestId: '{{ old('interest_id', $data->interest_id) }}',
                subInterestId: '{{ old('sub_interest_id', $data->sub_interest_id) }}',
                subInterests: [],

                async fetchSubInterests() {
                    if (!this.interestId) {
                        this.subInterests = [];
                        return;
                    }

                    console.log(this.interestId);

                    const res = await fetch(`/api/get-sub_interest/${this.interestId}`);
                    this.subInterests = await res.json();
                },

                async init() {
                    if (this.interestId) {
                        await this.fetchSubInterests();
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
