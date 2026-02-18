@extends('layouts.app')

@section('title', 'ADMIN | Tingkat Kekhataman')

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
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Tingkat Kekhataman</h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <button @click="showFilterCollapse = !showFilterCollapse"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200">
                                <svg class="stroke-current fill-white dark:fill-gray-800" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.29004 5.90393H17.7067" stroke="" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M17.7075 14.0961H2.29085" stroke="" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M12.0826 3.33331C13.5024 3.33331 14.6534 4.48431 14.6534 5.90414C14.6534 7.32398 13.5024 8.47498 12.0826 8.47498C10.6627 8.47498 9.51172 7.32398 9.51172 5.90415C9.51172 4.48432 10.6627 3.33331 12.0826 3.33331Z"
                                        fill="" stroke="" stroke-width="1.5" />
                                    <path
                                        d="M7.91745 11.525C6.49762 11.525 5.34662 12.676 5.34662 14.0959C5.34661 15.5157 6.49762 16.6667 7.91745 16.6667C9.33728 16.6667 10.4883 15.5157 10.4883 14.0959C10.4883 12.676 9.33728 11.525 7.91745 11.525Z"
                                        fill="" stroke="" stroke-width="1.5" />
                                </svg>

                                Filter
                            </button>

                            <a class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                                href="{{ route('admin.levels.index') }}">
                                See all
                            </a>
                        </div>
                    </div>

                    <div x-show="showFilterCollapse">
                        <form x-data="filterForm()" action="{{ route('admin.levels.index') }}" method="GET">
                            <div
                                class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Filter Pengguna</h2>
                            </div>

                            <div class="grid grid-cols-1 gap-3 mt-5 space-y-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Daerah
                                    </label>
                                    <select name="zone_id"
                                        class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                                        x-model="zone_id">
                                        <option selected value="">Semua Daerah</option>
                                        @foreach ($zones as $zone)
                                            <option
                                                {{ isset($filters['zone_id']) ? ($filters['zone_id'] == $zone->id ? 'selected' : '') : '' }}
                                                value="{{ $zone->id }}">
                                                {{ $zone->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Kategori Umur
                                    </label>
                                    <select name="age_category_id"
                                        class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500">
                                        <option selected value="">Semua Kategori</option>
                                        @foreach ($age_categories as $age_category)
                                            <option
                                                {{ isset($filters['age_category_id']) ? ($filters['age_category_id'] == $age_category->id ? 'selected' : '') : '' }}
                                                value="{{ $age_category->id }}">
                                                {{ $age_category->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Jenis Kelamin
                                    </label>
                                    <select name="kelamin"
                                        class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500">
                                        <option selected value="">Semua Jenis Kelamin</option>
                                        <option
                                            {{ isset($filters['kelamin']) ? ($filters['kelamin'] == 'LK' ? 'selected' : '') : '' }}
                                            value="LK">
                                            Laki-laki
                                        </option>
                                        <option
                                            {{ isset($filters['kelamin']) ? ($filters['kelamin'] == 'PR' ? 'selected' : '') : '' }}
                                            value="PR">
                                            Perempuan
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" @click="showFilterCollapse = false"
                                    class="px-4 py-2 text-sm text-gray-700 border border-gray-400 rounded-lg hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                                    Batal
                                </button>
                                <button @click="applyFilter()"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    Terapkan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full whitespace-nowrap">
                            <!-- table header start -->
                            <thead>
                                <tr class="border-gray-100 border-y dark:border-gray-800">
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        No.
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Nama
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                        Kategori Umur
                                    </th>
                                    @if (Auth::user()->role === 'MASTER')
                                        <th
                                            class="py-3 pr-10 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                            Daerah
                                        </th>
                                    @endif

                                    {{-- Tambahkan semua level di sini --}}
                                    @foreach ($metafieldLevels as $metafieldLevel)
                                        <th
                                            class="px-5 py-3 font-medium text-center text-gray-500 text-theme-xs dark:text-gray-400">
                                            {{ $metafieldLevel->field_name }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <!-- table header end -->

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($users as $item)
                                    <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-white/5">
                                        <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.levels.edit', $item->id) }}">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-[50px] w-[50px] overflow-hidden rounded-md">
                                                        <img src="{{ $item->gambar ? $item->gambar : 'https://ui-avatars.com/api/?name=' . urlencode($item->nama) . '&background=random' }}"
                                                            alt="Profile" />
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="font-bold text-blue-500 underline text-theme-sm dark:text-white/90">
                                                            {{ $item->nama }}
                                                        </p>
                                                        <span class="text-gray-500 text-theme-xs dark:text-gray-400">
                                                            {{ $item->role }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        </td>

                                        <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $item->ageCategory?->nama ?? 'Tidak diatur' }}
                                        </td>
                                        @if (Auth::user()->role === 'MASTER')
                                            <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $item->zone->nama ?? 'Tidak diatur' }}
                                            </td>
                                        @endif

                                        {{-- Level columns --}}
                                        @php
                                            $level = $item->level;
                                        @endphp

                                        @foreach ($metafieldLevels as $metafieldLevel)
                                            @if (isset($level))
                                                @php
                                                    $metaFieldLevelUser = $level
                                                        ->where('metafield_level_id', $metafieldLevel->id)
                                                        ->first();
                                                @endphp

                                                @if ($metaFieldLevelUser)
                                                    <td
                                                        class="{{ $metaFieldLevelUser->value ? ($metaFieldLevelUser->value >= 70 ? 'bg-green-100' : ($metaFieldLevelUser->value >= 40 ? 'bg-yellow-100' : ($metaFieldLevelUser->value > 0 ? 'bg-red-100' : ''))) : '' }} py-3 text-center text-gray-500 text-theme-sm dark:text-gray-400">
                                                        @if ($metaFieldLevelUser->status === 'PENDING')
                                                            <p class="text-xs">Diubah oleh user</p>
                                                        @endif

                                                        {{ $metaFieldLevelUser->value ?? 0 }}%
                                                    </td>
                                                @else
                                                    <td
                                                        class="py-3 text-center text-gray-500 text-theme-sm dark:text-gray-400">
                                                        0%
                                                    </td>
                                                @endif
                                            @else
                                                <td class="py-3 text-center text-gray-500 text-theme-sm dark:text-gray-400">
                                                    0%
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="24" class="p-4 text-center text-gray-500 text-theme-xs">
                                            Data Kosong
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <x-pagination :data="$users" />
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

    <script>
        function filterForm() {
            return {
                // === INIT OLD VALUE ===
                zone_id: '{{ isset($filters['zone_id']) ? $filters['zone_id'] : '' }}',
            };
        }
    </script>
@endpush
