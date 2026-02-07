@extends("layouts.app")

@section("title", "ADMIN | History Presensi")

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
                        <div x-data="{ showDetail: false }">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">History Presensi</h3>
                            <h4 class="font-normal text-indigo-500 dark:text-white/90">
                                {{ $activity->nama }} ({{ $activity->nama_hari }})
                            </h4>
                            <a
                                @click="showDetail = !showDetail"
                                class="flex flex-row items-center text-sm font-light text-blue-500 cursor-pointer"
                            >
                                <p>Lihat detail nya</p>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <div x-show="showDetail" x-transition class="mt-2">
                                <h5 class="text-xs font-light text-gray-500 dark:text-white/90">
                                    Daerah : {{ $activity->zone->nama ?? "Daerah tidak diatur" }}
                                </h5>
                                <h5 class="text-xs font-light text-gray-500 dark:text-white/90">
                                    Desa : {{ $activity->village->nama ?? "Desa tidak diatur" }}
                                </h5>
                                <h5 class="text-xs font-light text-gray-500 dark:text-white/90">
                                    Kelompok : {{ $activity->group->nama ?? "Desa tidak diatur" }}
                                </h5>
                            </div>
                        </div>
                        <div>
                            <form id="filter_tanggal" class="relative">
                                <input
                                    value="{{ $filter_tanggal }}"
                                    name="tanggal_presensi"
                                    id="tanggal_presensi"
                                    type="date"
                                    placeholder="Select date"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    onclick="this.showPicker()"
                                />
                                <span
                                    class="absolute text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400"
                                >
                                    <svg
                                        class="fill-current"
                                        width="20"
                                        height="20"
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                            fill=""
                                        />
                                    </svg>
                                </span>
                            </form>
                        </div>
                    </div>

                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full">
                            <!-- table header start -->
                            <thead>
                                <tr class="border-gray-100 border-y dark:border-gray-800">
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400"
                                    >
                                        No.
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400"
                                    >
                                        Nama
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400"
                                    >
                                        Jam Datang
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400"
                                    >
                                        Keterangan
                                    </th>
                                </tr>
                            </thead>
                            <!-- table header end -->

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($users as $item)
                                    <tr data-user-id="{{ $item->id }}">
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            {{ $item->nama }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            <p>
                                                {{ $item->presence_today?->jam_datang ?? "-" }}
                                            </p>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            <span
                                                class="{{ $item->presence_color }} p-2 rounded text-xs font-semibold uppercase"
                                            >
                                                {{ $item->presence_status }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            <p>
                                                {{ $item->presence_today?->keterangan ?? "-" }}
                                            </p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="p-4 text-center text-gray-500 text-theme-xs">
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

    <script>
        const dayData = @json($activity->hari);
        const dayDataName = @json($activity->nama_hari);
        let dayFormat = 0;

        if (dayData == 7) {
            dayFormat = 0;
        } else {
            dayFormat = dayData;
        }

        document.getElementById('tanggal_presensi').addEventListener('change', function () {
            const date = new Date(this.value);
            const day = date.getDay();

            if (day != dayFormat) {
                alert(`Aktifitas hanya di hari ${dayDataName}`);
                this.value = '';
            }

            const filterTanggal = document.getElementById('filter_tanggal');
            filterTanggal.submit();
        });
    </script>
@endpush
