@extends("layouts.app")

@section("title", "ADMIN | Data Kehadiran")

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
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Kehadiran</h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <button
                                @click="showFilterCollapse = !showFilterCollapse"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                            >
                                <svg
                                    class="stroke-current fill-white dark:fill-gray-800"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 20 20"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M2.29004 5.90393H17.7067"
                                        stroke=""
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                    <path
                                        d="M17.7075 14.0961H2.29085"
                                        stroke=""
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                    <path
                                        d="M12.0826 3.33331C13.5024 3.33331 14.6534 4.48431 14.6534 5.90414C14.6534 7.32398 13.5024 8.47498 12.0826 8.47498C10.6627 8.47498 9.51172 7.32398 9.51172 5.90415C9.51172 4.48432 10.6627 3.33331 12.0826 3.33331Z"
                                        fill=""
                                        stroke=""
                                        stroke-width="1.5"
                                    />
                                    <path
                                        d="M7.91745 11.525C6.49762 11.525 5.34662 12.676 5.34662 14.0959C5.34661 15.5157 6.49762 16.6667 7.91745 16.6667C9.33728 16.6667 10.4883 15.5157 10.4883 14.0959C10.4883 12.676 9.33728 11.525 7.91745 11.525Z"
                                        fill=""
                                        stroke=""
                                        stroke-width="1.5"
                                    />
                                </svg>

                                Filter
                            </button>

                            <a
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                                href="{{ route("admin.users.index") }}"
                            >
                                See all
                            </a>
                        </div>
                    </div>

                    <div x-show="showFilterCollapse">
                        <form action="{{ route("user.user_presences.index") }}" method="GET">
                            <div
                                class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700"
                            >
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Filter Kehadiran</h2>
                            </div>

                            <div
                                class="grid grid-cols-1 gap-3 mt-5 space-y-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                            >
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Jadwal Kegiatan
                                    </label>
                                    <select
                                        x-model="filterVillageId"
                                        name="activity_id"
                                        class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="all">Semua Kegiatan</option>
                                        @foreach ($activities as $activity)
                                            <option value="{{ $activity->id }}">
                                                {{ $activity->nama }} ({{ strtolower($activity->type) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 mt-6">
                                <button
                                    type="button"
                                    @click="showFilterCollapse = false"
                                    class="px-4 py-2 text-sm text-gray-700 border border-gray-400 rounded-lg hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800"
                                >
                                    Batal
                                </button>
                                <button
                                    @click="applyFilter()"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                                >
                                    Terapkan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full">
                            <!-- table header start -->
                            <thead>
                                <tr class="border-gray-100 border-y dark:border-gray-800">
                                    <th class="py-3">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                No.
                                            </p>
                                        </div>
                                    </th>
                                    <th class="py-3">
                                        <div class="flex items-center col-span-2">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Nama Kegiatan
                                            </p>
                                        </div>
                                    </th>
                                    <th class="py-3">
                                        <div class="flex items-center col-span-2">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Tipe Kegiatan
                                            </p>
                                        </div>
                                    </th>
                                    <th class="py-3">
                                        <div class="flex items-center col-span-2">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Jam datang
                                            </p>
                                        </div>
                                    </th>
                                    <th class="py-3">
                                        <div class="flex items-center col-span-2">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Status
                                            </p>
                                        </div>
                                    </th>
                                    <th class="py-3">
                                        <div class="flex items-center col-span-2">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Keterangan
                                            </p>
                                        </div>
                                    </th>
                                    {{--
                                        <th class="py-3">
                                        <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Aksi
                                        </p>
                                        </div>
                                        </th>
                                    --}}
                                </tr>
                            </thead>
                            <!-- table header end -->

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($presences as $item)
                                    <tr>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $loop->iteration }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $item->activity?->nama ?? "Tidak diatur" }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $item->activity?->type ?? "Tidak diatur" }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $item->jam_datang ?? "Tidak diatur" }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <p
                                                    class="rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500"
                                                >
                                                    {{ $item->status }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $item->keterangan ?? "Tidak diatur" }}
                                                </p>
                                            </div>
                                        </td>

                                        {{--
                                            <td class="py-3">
                                            <div class="flex items-center gap-2">
                                            <a
                                            href="{{ route("admin.users.show", $item->id) }}"
                                            class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-yellow-500 rounded-lg shadow-theme-xs hover:bg-yellow-600"
                                            >
                                            <i class="fa-regular fa-eye"></i>
                                            <p class="hidden text-theme-sm md:block">Detail</p>
                                            </a>
                                            <form
                                            action="{{ route("admin.users.destroy", $item->id) }}"
                                            method="POST"
                                            >
                                            @csrf
                                            @method("DELETE")
                                            <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-red-500 rounded-lg shadow-theme-xs hover:bg-red-600"
                                            >
                                            <i class="fa-regular fa-trash-can"></i>
                                            <p class="hidden text-theme-sm md:block">Hapus</p>
                                            </button>
                                            </form>
                                            </div>
                                            </td>
                                        --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-gray-500 text-theme-xs">
                                            Data Kosong
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <x-pagination :data="$presences" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
