@extends('layouts.app')

@section('title', 'ADMIN | Data Kegiatan')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="space-y-5 sm:space-y-6">
                <div x-data="{
                    showFilterCollapse: false,
                    applyFilter() {
                        console.log('Filter diterapkan:', this.filterStatus, this.filterDate);
                        this.showFilterCollapse = false;
                    }
                }"
                    class="px-4 pt-4 pb-3 overflow-hidden bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5 sm:px-6">
                    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Kegiatan</h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex items-center">
                                <a class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                                    href="{{ route('admin.activities.create') }}">
                                    <i class="fa-regular fa-plus fa-lg"></i>
                                    Tambah
                                </a>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ notificationModal: false, zone_id: null, activity_id: null }" class="w-full px-2 overflow-x-auto sm:px-0">
                        <table class="min-w-full whitespace-nowrap">
                            <thead>
                                <tr class="border-gray-100 border-y dark:border-gray-800">
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        No.
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Nama Kegiatan
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Tipe Kegiatan
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Peserta
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Waktu
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Jam
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Materi
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Tempat
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Daerah
                                    </th>

                                    <th
                                        class="px-4 py-3 text-xs font-medium text-left text-gray-500 dark:text-gray-400 min-w-[100px]">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($activities as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            {{ $item->nama ?? 'Tidak diatur' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            {{ $item->type ?? 'Tidak diatur' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            @if ($item->for_status_kawin)
                                                @foreach (json_decode($item->for_status_kawin) as $sk)
                                                    {{ strtolower($sk) }} menikah,
                                                @endforeach
                                            @endif

                                            @foreach ($item->ageCategories as $age)
                                                {{ $age->nama }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            {{ $item->tanggal ?? $item->nama_hari }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            {{ $item->jam }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            {{ $item->materi }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            {{ $item->tempat }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            {{ $item->zone->nama ?? 'Belum diatur' }}
                                        </td>

                                        <td class="py-3">
                                            <div class="flex items-center gap-2">
                                                <a type="button" href="{{ route('admin.activities.edit', $item->id) }}"
                                                    class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-yellow-500 rounded-lg shadow-theme-xs hover:bg-yellow-600">
                                                    <i class="fa-regular fa-edit"></i>
                                                    <p class="hidden text-theme-sm md:block">Edit</p>
                                                </a>

                                                <form action="{{ route('admin.send_recap') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="activity_id" value="{{ $item->id }}" />
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                                        <i class="fa-solid fa-paper-plane"></i>
                                                        Kirim Rekap
                                                    </button>
                                                </form>

                                                <button type="button"
                                                    @click="notificationModal = true; zone_id = '{{ $item->zone_id }}'; activity_id = '{{ $item->id }}';"
                                                    class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-gray-500 rounded-lg shadow-theme-xs hover:bg-gray-600">
                                                    <i class="fa-regular fa-bell"></i>
                                                    <p class="hidden text-theme-sm md:block">Notifikasi</p>
                                                </button>

                                                <form id="delete-form-{{ $item->id }}"
                                                    action="{{ route('admin.activities.destroy', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        onclick="confirmDelete('delete-form-{{ $item->id }}')"
                                                        class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-red-500 rounded-lg shadow-theme-xs hover:bg-red-600">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                        <p class="hidden text-theme-sm md:block">Hapus</p>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="p-4 text-center text-gray-500 text-theme-xs">
                                                Data Kosong
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <x-pagination :data="$activities" />

                            <div x-show="notificationModal" x-transition x-trap.inert.noscroll="notificationModal"
                                class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog">
                                <div x-show="notificationModal" x-transition.opacity
                                    class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

                                <div @click.outside="notificationModal = false" x-transition.scale
                                    class="relative w-full max-w-md p-6 bg-white border border-gray-200 shadow-xl rounded-2xl dark:bg-gray-900 dark:border-gray-800">
                                    <div class="flex items-center justify-between mb-4">
                                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                                            Kirim Notifikasi
                                        </h2>
                                        <button @click="notificationModal = false"
                                            class="text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            <i class="fa-solid fa-xmark fa-lg"></i>
                                        </button>
                                    </div>

                                    <form action="{{ route('admin.notifications.store') }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('POST')

                                        <input type="hidden" name="fromActivity" value="fromActivity" />
                                        <input type="hidden" name="jenis_notification" value="SEKARANG" />
                                        <input type="hidden" name="zone_id" :value="zone_id" />
                                        <input type="hidden" name="activity_id" :value="activity_id" />

                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Judul
                                            </label>
                                            <input name="judul"
                                                class="w-full px-3 py-2 text-sm border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                                                placeholder="Tuliskan judul" />
                                        </div>

                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Pesan
                                            </label>
                                            <textarea rows="3" name="message"
                                                class="w-full px-3 py-2 text-sm border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                                                placeholder="Tuliskan pesan notifikasi..."></textarea>
                                        </div>

                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="notificationModal = false"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                class="px-4 py-2 text-sm font-medium text-white rounded-lg shadow bg-brand-500 hover:bg-brand-600">
                                                Kirim
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            }
        </script>
    @endpush
