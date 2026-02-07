@extends('layouts.app')

@section('title', 'ADMIN | Detail Minat')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="space-y-5 sm:space-y-6">

                <div
                    class="px-4 pt-4 pb-3 overflow-hidden bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5 sm:px-6">

                    {{-- HEADER --}}
                    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                Detail Minat
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $interest->nama }}
                            </p>
                        </div>

                        <a href="{{ route('admin.interests.index') }}"
                            class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>

                    {{-- FORM TAMBAH SUB MINAT --}}
                    <form action="{{ route('admin.sub_interests.store') }}" method="POST"
                        class="flex flex-col gap-3 mb-5 sm:flex-row">
                        @csrf

                        <input type="hidden" name="interest_id" value="{{ $interest->id }}">

                        <input type="text" name="nama" placeholder="Tambah sub minat"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                            required>

                        <button
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white transition rounded-lg bg-brand-500 hover:bg-brand-600 shadow-theme-xs">
                            <i class="fa-regular fa-plus"></i>
                            Tambah
                        </button>
                    </form>

                    {{-- TABLE SUB MINAT --}}
                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-gray-100 border-y dark:border-gray-800">
                                    <th class="py-3">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No.</p>
                                    </th>
                                    <th class="py-3">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Nama Sub Minat
                                        </p>
                                    </th>
                                    <th class="py-3">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($interest->subInterests as $sub)
                                    <tr x-data="{ edit: false }">
                                        <td class="py-3">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $loop->iteration }}
                                            </p>
                                        </td>

                                        <td class="py-3">
                                            {{-- VIEW MODE --}}
                                            <p x-show="!edit"
                                                class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                {{ $sub->nama }}
                                            </p>

                                            {{-- EDIT MODE --}}
                                            <form x-show="edit" x-transition
                                                action="{{ route('admin.sub_interests.update', $sub->id) }}" method="POST"
                                                class="flex items-center gap-2">
                                                @csrf
                                                @method('PUT')

                                                <input type="text" name="nama" value="{{ $sub->nama }}"
                                                    class="h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                                    required>

                                                <button
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600">
                                                    Simpan
                                                </button>
                                            </form>
                                        </td>

                                        <td class="py-3">
                                            <div class="flex items-center gap-2">

                                                {{-- EDIT --}}
                                                <button @click="edit = !edit" type="button"
                                                    class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-yellow-500 rounded-lg shadow-theme-xs hover:bg-yellow-600">
                                                    <i class="fa-regular fa-edit"></i>
                                                    <p class="hidden md:block">Ubah</p>
                                                </button>

                                                {{-- DELETE --}}
                                                <form id="delete-sub-{{ $sub->id }}"
                                                    action="{{ route('admin.sub_interests.destroy', $sub->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        onclick="confirmDelete('delete-sub-{{ $sub->id }}')"
                                                        class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-red-500 rounded-lg shadow-theme-xs hover:bg-red-600">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                        <p class="hidden md:block">Hapus</p>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-4 text-center text-gray-500 text-theme-xs">
                                            Belum ada sub minat
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

    <script>
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Yakin hapus?',
                text: 'Data tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
@endpush
