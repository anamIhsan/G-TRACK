@extends('layouts.app')
@section('title', 'Settings')
@section('content')
    <div class="p-5 bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5 lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Pengaturan</h3>

        @php
            $role = strtolower(str_replace('_', ' ', $user->role)) ?? 'guest';
        @endphp

        <div class="row">
            <div class="mb-4 col-lg-6">
                <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
                    <div class="card-body">
                        <h5 class="mb-3 text-gray-800 dark:text-white/90">Profil {{ $role }}</h5>

                        <form action="{{ route('setting.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="update_type" value="profile" />

                            <div class="mb-3">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nama {{ $role }}:
                                </label>
                                <input type="text"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required />
                                @error('nama')
                                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nomor HP {{ $role }}:
                                </label>
                                <input type="text"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    id="no_tlp" name="no_tlp" value="{{ old('no_tlp', $user->no_tlp) }}" required />
                                @error('no_tlp')
                                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4 text-end">
                                <button
                                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
                                    type="submit">
                                    <i class="fas fa-save me-2"></i>
                                    Simpan Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mb-4 col-lg-6">
                <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
                    <div class="card-body">
                        <h5 class="mb-3 text-gray-800 dark:text-white/90">Ubah Password</h5>

                        <form action="{{ route('setting.update', Auth::user()->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="update_type" value="password" />

                            <div class="mb-3">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Password Lama:
                                </label>
                                <input type="password"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    id="password_lama" name="password_lama" required />
                                @error('password_lama')
                                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Password Baru:
                                </label>
                                <input type="password"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    id="password_baru" name="password_baru" required />
                                @error('password_baru')
                                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Konfirmasi Password Baru:
                                </label>
                                <input type="password"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    id="password_baru_confirmation" name="password_baru_confirmation" required />
                                @error('password_baru_confirmation')
                                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4 text-end">
                                <button
                                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
                                    type="submit">
                                    <i class="fas fa-key me-2"></i>
                                    Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if ($user->role != 'USER')
                <div class="mb-4 col-lg-6">
                    <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
                        <div class="card-body">
                            <h5 class="mb-3 text-gray-800 dark:text-white/90">Whatsapp Gateway</h5>

                            <form x-data="{
                                whatsapp_gateway_provider: '{{ old('whatsapp_gateway_provider', $user->whatsapp_gateway_provider) }}',
                            }" action="{{ route('admin.setting.whatsapp-gateway') }}"
                                method="POST">
                                @csrf
                                @method('POST')
                                <div class="mb-3">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Pilih Provider
                                    </label>
                                    <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                        <select name="whatsapp_gateway_provider"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                            @change="isOptionSelected = true; whatsapp_gateway_provider = $event.target.value;">
                                            <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled
                                                selected>
                                                -- Pilih Salah Satu --
                                            </option>
                                            <option
                                                {{ old('whatsapp_gateway_provider', $user->whatsapp_gateway_provider) === 'FONTEE' ? 'selected' : '' }}
                                                value="FONTEE" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Fontee
                                            </option>
                                            <option
                                                {{ old('whatsapp_gateway_provider', $user->whatsapp_gateway_provider) === 'WABLAS' ? 'selected' : '' }}
                                                value="WABLAS" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Wablas
                                            </option>
                                            <option
                                                {{ old('whatsapp_gateway_provider', $user->whatsapp_gateway_provider) === 'FLOWKIRIM' ? 'selected' : '' }}
                                                value="FLOWKIRIM" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Flowkirim
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

                                <div x-transition x-show="whatsapp_gateway_provider === 'FONTEE'" class="mb-3">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Fonnte Token
                                    </label>
                                    <input type="text"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        id="fontee_token" name="fontee_token"
                                        value="{{ old('fontee_token', isset($user->whatsappGateway) ? $user->whatsappGateway->config['fontee_token'] ?? '' : '') }}" />
                                    @error('fontee_token')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div x-transition x-show="whatsapp_gateway_provider === 'WABLAS'" class="mb-3">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Wablas Token
                                    </label>
                                    <input type="text"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        id="wablas_token" name="wablas_token"
                                        value="{{ old('wablas_token', isset($user->whatsappGateway) ? $user->whatsappGateway->config['wablas_token'] ?? '' : '') }}" />
                                    @error('wablas_token')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div x-transition x-show="whatsapp_gateway_provider === 'WABLAS'" class="mb-3">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Wablas Secret Key
                                    </label>
                                    <input type="text"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        id="wablas_secret_key" name="wablas_secret_key"
                                        value="{{ old('wablas_secret_key', isset($user->whatsappGateway) ? $user->whatsappGateway->config['wablas_secret_key'] ?? '' : '') }}" />
                                    @error('wablas_secret_key')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div x-transition x-show="whatsapp_gateway_provider === 'FLOWKIRIM'" class="mb-3">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Device ID
                                    </label>
                                    <input type="text"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        id="flowkirim_deviceId" name="flowkirim_deviceId"
                                        value="{{ old('flowkirim_deviceId', isset($user->whatsappGateway) ? $user->whatsappGateway->config['flowkirim_deviceId'] ?? '' : '') }}" />
                                    @error('flowkirim_deviceId')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-4 text-end">
                                    <button @click="sendTestMessage()"
                                        class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
                                        type="button">
                                        Test
                                    </button>
                                    <button
                                        class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
                                        type="submit">
                                        <i class="fas fa-save me-2"></i>
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
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

    {{-- alert error --}}
    <script>
        if ({{ session()->has('error') }}) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
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
                confirmButtonText: 'Kirim!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    <script>
        function sendTestMessage() {
            Swal.fire({
                title: 'Kirim?',
                text: 'Kirim test notifikasi!',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Kirim!',
                cancelButtonText: 'Batal',
                input: 'number',
                inputPlaceholder: 'Masukan nomor telepon penerima',
            }).then((result) => {
                if (result.isConfirmed) {
                    const phone = result.value;

                    console.log(phone);

                    fetch(`/api/test/notif?phone=${phone}`)
                        .then((res) => res.json())
                        .then((data) => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                });
                            }
                            if (data.status === 'error') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message,
                                });
                            }
                            console.log('message:', data.message);
                        })
                        .catch((err) => {
                            console.error(err);
                        });
                }
            });
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
