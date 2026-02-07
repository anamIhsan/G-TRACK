@extends('layouts.app')

@section('title', 'ADMIN | Data Notifikasi')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="space-y-5 sm:space-y-6">
                <div x-data="{
                    showFilterCollapse: false,
                    applyFilter() {
                        console.log('Filter diterapkan:', this.filterStatus, this.filterDate)
                        this.showFilterCollapse = false
                    },
                }"
                    class="px-4 pt-4 pb-3 overflow-hidden bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5 sm:px-6">
                    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Notifikasi</h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex items-center">
                                <a class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                                    href="{{ route('admin.notifications.create') }}">
                                    <i class="fa-regular fa-plus fa-lg"></i>
                                    Tambah
                                </a>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ notificationModal: false, selectedActivity: null }" class="w-full overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-gray-100 border-y dark:border-gray-800">
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        No.
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        judul
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Pesan
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Jenis Notifikasi
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Jenis Pengiriman
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Tanggal
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Jam
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($notifications as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $item->judul }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $item->message }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $item->jenis_notification ?? 'Tidak diatur' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $item->jenis_pengiriman ?? 'Tidak diatur' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $item->tanggal ?? 'Tidak diatur' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $item->jam ?? 'Tidak diatur' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <a type="button" href="{{ route('admin.notifications.edit', $item->id) }}"
                                                    class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-yellow-500 rounded-lg shadow-theme-xs hover:bg-yellow-600">
                                                    <i class="fa-regular fa-edit"></i>
                                                    <p class="hidden text-theme-sm md:block">Edit</p>
                                                </a>

                                                <form id="delete-form-{{ $item->id }}"
                                                    action="{{ route('admin.notifications.destroy', $item->id) }}"
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
                                        <td colspan="8" class="p-4 text-center text-gray-500 text-theme-xs">
                                            Data Kosong
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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

    <script>
        if ({{ session()->has('error') }}) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
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
