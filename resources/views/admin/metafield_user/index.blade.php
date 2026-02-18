@extends("layouts.app")

@section("title", "ADMIN | Data Custom Kolom Pengguna")

@section("content")
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="space-y-5 sm:space-y-6">
                <div
                    x-data="{
                    showFilterCollapse: false,
                    applyFilter() {
                        console.log('Filter diterapkan:', this.filterStatus, this.filterDate)
                        this.showFilterCollapse = false
                    },
                }"
                    class="px-4 pt-4 pb-3 overflow-hidden bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5 sm:px-6"
                >
                    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                Daftar Custom Kolom Pengguna
                            </h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex items-center">
                                <a
                                    class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                                    href="{{ route("admin.metafield_user.create") }}"
                                >
                                    <i class="fa-regular fa-plus fa-lg"></i>
                                    Tambah
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full whitespace-nowrap">
                            <!-- table header start -->
                            <thead>
                                <tr class="border-gray-100 border-y dark:border-gray-800">
                                    <th class="px-4 py-3">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                No.
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3">
                                        <div class="flex items-center col-span-2">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Nama Kolom
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3">
                                        <div class="flex items-center col-span-2">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Tipe
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3">
                                        <div class="flex items-center col-span-2">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Isi Pilihan
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Aksi
                                            </p>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <!-- table header end -->

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($metafieldUsers as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $loop->iteration }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <button type="button" @click="showDetail({{ $item }})">
                                                <div class="flex items-center">
                                                    <div class="flex items-center gap-3">
                                                        <p
                                                            class="font-medium text-blue-500 hover:underline text-theme-sm dark:text-white/90"
                                                        >
                                                            {{ $item->field }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </button>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="flex items-center gap-3">
                                                    <p
                                                        class="font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                                    >
                                                        {{ $item->type }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="flex items-center gap-3">
                                                    <p
                                                        class="font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                                    >
                                                        @if ($item->type === "ENUM")
                                                            @forelse (json_decode($item->enum_values) as $value)
                                                                {{ $value }}@if (! $loop->last),
                                                                @endif
                                                            @empty
                                                                Value tidak diatur
                                                            @endforelse
                                                        @else
                                                            -
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="flex items-center gap-3">
                                                    <p
                                                        class="font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                                    >
                                                        {{ $item->zone->nama ?? "Belum diatur" }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <a
                                                    href="{{ route("admin.metafield_user.edit", $item->id) }}"
                                                    class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-yellow-500 rounded-lg shadow-theme-xs hover:bg-yellow-600"
                                                >
                                                    <i class="fa-regular fa-edit"></i>
                                                    <p class="hidden text-theme-sm md:block">Ubah</p>
                                                </a>
                                                <form
                                                    id="delete-form-{{ $item->id }}"
                                                    action="{{ route("admin.metafield_user.destroy", $item->id) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method("DELETE")
                                                    <button
                                                        type="button"
                                                        onclick="confirmDelete('delete-form-{{ $item->id }}')"
                                                        class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-red-500 rounded-lg shadow-theme-xs hover:bg-red-600"
                                                    >
                                                        <i class="fa-regular fa-trash-can"></i>
                                                        <p class="hidden text-theme-sm md:block">Hapus</p>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-gray-500 text-theme-xs">
                                            Data Kosong
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <x-pagination :data="$metafieldUsers" />
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
