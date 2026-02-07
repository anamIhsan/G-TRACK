@extends("layouts.app")

@section("title", "ADMIN | Data Minat")

@section("content")
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="space-y-5 sm:space-y-6">
                <div
                    class="px-4 pt-4 pb-3 overflow-hidden bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5 sm:px-6"
                >
                    {{-- HEADER --}}
                    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Minat</h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <a
                                class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                                href="{{ route("admin.interests.create") }}"
                            >
                                <i class="fa-regular fa-plus fa-lg"></i>
                                Tambah
                            </a>
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-gray-100 border-y dark:border-gray-800">
                                    <th class="px-3 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs">No</p>
                                    </th>

                                    <th class="px-3 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs">Nama Minat</p>
                                    </th>

                                    {{-- KOLOM ZONA --}}
                                    <th class="px-3 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs">Zona</p>
                                    </th>

                                    <th class="px-3 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs">Aksi</p>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($interests as $interest)
                                    <tr>
                                        {{-- NO --}}
                                        <td class="px-3 py-3">
                                            <p class="text-gray-500 text-theme-sm">
                                                {{ $loop->iteration }}
                                            </p>
                                        </td>

                                        {{-- NAMA MINAT --}}
                                        <td class="px-3 py-3">
                                            <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                {{ $interest->nama }}
                                            </p>
                                        </td>

                                        {{-- ZONA --}}
                                        <td class="px-3 py-3">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300"
                                            >
                                                {{ $interest->zone?->nama ?? "-" }}
                                            </span>
                                        </td>

                                        {{-- AKSI --}}
                                        <td class="px-3 py-3">
                                            <div class="flex items-center gap-2">
                                                {{-- DETAIL --}}
                                                <a
                                                    href="{{ route("admin.interests.show", $interest->id) }}"
                                                    class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-600"
                                                >
                                                    <i class="fa-regular fa-eye"></i>
                                                    <span class="hidden md:block">Detail</span>
                                                </a>

                                                {{-- EDIT --}}
                                                <a
                                                    href="{{ route("admin.interests.edit", $interest->id) }}"
                                                    class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600"
                                                >
                                                    <i class="fa-regular fa-edit"></i>
                                                    <span class="hidden md:block">Ubah</span>
                                                </a>

                                                {{-- DELETE --}}
                                                <form
                                                    id="delete-{{ $interest->id }}"
                                                    action="{{ route("admin.interests.destroy", $interest->id) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method("DELETE")
                                                    <button
                                                        type="button"
                                                        onclick="confirmDelete('delete-{{ $interest->id }}')"
                                                        class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600"
                                                    >
                                                        <i class="fa-regular fa-trash-can"></i>
                                                        <span class="hidden md:block">Hapus</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-4 text-center text-gray-500 text-theme-xs">
                                            Data minat belum tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <x-pagination :data="$interests" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- alert success --}}
    <script>
        if ({{ session()->has("success") }}) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
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
