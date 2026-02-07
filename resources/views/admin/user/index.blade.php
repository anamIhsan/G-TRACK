@extends("layouts.app")

@section("title", "ADMIN | Data Pengguna")

@section("content")
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="space-y-5 sm:space-y-6">
                <div
                    x-data="{
                        showFilterCollapse: false,
                    }"
                    class="px-4 pt-4 pb-3 overflow-hidden bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5 sm:px-6"
                >
                    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                Daftar Pengguna {{ isset($age_category_nama) ? $age_category_nama : "" }}
                            </h3>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
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
                                @click="loaded = true"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                                href="{{ route("admin.export.users.store", ["village_id" => $filters["village_id"] ?? "", "group_id" => $filters["group_id"] ?? "", "kelamin" => $filters["kelamin"] ?? "", "interest_id" => $filters["interest_id"] ?? "", "sub_interest_id" => $filters["sub_interest_id"] ?? "", "community_id" => $filters["community_id"] ?? ""]) }}"
                            >
                                Export
                            </a>

                            <a
                                @click="loaded = true"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                                href="{{ route("admin.import.users.index") }}"
                            >
                                Import
                            </a>

                            <a
                                @click="loaded = true"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                                href="{{ route("admin.users.index") }}"
                            >
                                See all
                            </a>

                            <div class="flex items-center">
                                <a
                                    @click="loaded = true"
                                    class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                                    href="{{ route("admin.users.create") }}"
                                >
                                    <i class="fa-regular fa-plus fa-lg"></i>
                                    Tambah
                                </a>
                            </div>
                        </div>
                    </div>

                    <div x-show="showFilterCollapse">
                        <form x-data="filterForm()" action="{{ route("admin.users.index") }}" method="GET">
                            <div
                                class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700"
                            >
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Filter Pengguna</h2>
                            </div>

                            <div
                                class="grid grid-cols-1 gap-3 mt-5 space-y-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                            >
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Daerah
                                    </label>
                                    <select
                                        name="zone_id"
                                        class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                                        x-model="zone_id"
                                        @change="getVillages(zone_id)"
                                    >
                                        <option selected value="">Semua Daerah</option>
                                        @foreach ($zones as $zone)
                                            <option
                                                {{ isset($filters["zone_id"]) ? ($filters["zone_id"] == $zone->id ? "selected" : "") : "" }}
                                                value="{{ $zone->id }}"
                                            >
                                                {{ $zone->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Desa
                                    </label>
                                    <select
                                        name="village_id"
                                        class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                                        x-model="village_id"
                                        @change="getGroups(village_id)"
                                    >
                                        <option selected value="">Semua Desa</option>
                                        <template x-for="v in villages" :key="v.id">
                                            <option
                                                :value="v.id"
                                                x-selected="v.id == village_id"
                                                x-text="v.nama"
                                            ></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Kelompok
                                    </label>
                                    <select
                                        name="group_id"
                                        class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                                        x-model="group_id"
                                    >
                                        <option selected value="">Semua Kelompok</option>
                                        <template x-for="g in groups" :key="g.id">
                                            <option
                                                :value="g.id"
                                                x-selected="g.id == group_id"
                                                x-text="g.nama"
                                            ></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Jenis Kelamin
                                    </label>
                                    <select
                                        name="kelamin"
                                        class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option selected value="">Semua Jenis Kelamin</option>
                                        <option
                                            {{ isset($filters["kelamin"]) ? ($filters["kelamin"] == "LK" ? "selected" : "") : "" }}
                                            value="LK"
                                        >
                                            Laki-laki
                                        </option>
                                        <option
                                            {{ isset($filters["kelamin"]) ? ($filters["kelamin"] == "PR" ? "selected" : "") : "" }}
                                            value="PR"
                                        >
                                            Perempuan
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Minat
                                    </label>
                                    <select
                                        name="interest_id"
                                        class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option selected value="">Semua Minat</option>
                                        @foreach ($interests as $interest)
                                            <option
                                                {{ isset($filters["interest_id"]) ? ($filters["interest_id"] == $interest->id ? "selected" : "") : "" }}
                                                value="{{ $interest->id }}"
                                            >
                                                {{ $interest->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Sub Minat
                                    </label>
                                    <select
                                        name="sub_interest_id"
                                        class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option selected value="">Semua Sub Minat</option>
                                        @foreach ($sub_interests as $sub_interest)
                                            <option
                                                {{ isset($filters["sub_interest_id"]) ? ($filters["sub_interest_id"] == $sub_interest->id ? "selected" : "") : "" }}
                                                value="{{ $sub_interest->id }}"
                                            >
                                                {{ $sub_interest->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Komunitas
                                    </label>
                                    <select
                                        name="community_id"
                                        class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option selected value="">Semua Komunitas</option>
                                        @foreach ($communities as $community)
                                            <option
                                                {{ isset($filters["community_id"]) ? ($filters["community_id"] == $community->id ? "selected" : "") : "" }}
                                                value="{{ $community->id }}"
                                            >
                                                {{ $community->nama }}
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
                                    @click="loaded = true"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                                >
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
                                                Nama
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Username
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Email
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                No Telpon
                                            </p>
                                        </div>
                                    </th>
                                    @if (Auth::user()->role === "MASTER")
                                        <th class="px-4 py-3">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Daerah
                                                </p>
                                            </div>
                                        </th>
                                    @endif

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
                                @forelse ($users as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $loop->iteration }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-[50px] w-[50px] overflow-hidden rounded-md">
                                                        <img
                                                            src="{{ $item->gambar ?? "https://ui-avatars.com/api/?name=" . $item->nama . "&background=random" }}"
                                                            alt="Profile"
                                                            class="w-full h-full bg-cover"
                                                        />
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                                        >
                                                            {{ $item->nama }}
                                                        </p>
                                                        <span class="text-gray-500 text-theme-xs dark:text-gray-400">
                                                            {{ $item->role }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $item->username ?? "Tidak diatur" }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $item->email ?? "Tidak diatur" }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $item->no_tlp ?? "Tidak diatur" }}
                                                </p>
                                            </div>
                                        </td>
                                        @if (Auth::user()->role === "MASTER")
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $item->zone->nama ?? "Tidak diatur" }}
                                                    </p>
                                                </div>
                                            </td>
                                        @endif

                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <a
                                                    @click="loaded = true"
                                                    href="{{ route("admin.users.show", $item->id) }}"
                                                    class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-yellow-500 rounded-lg shadow-theme-xs hover:bg-yellow-600"
                                                >
                                                    <i class="fa-regular fa-eye"></i>
                                                    <p class="hidden text-theme-sm md:block">Detail</p>
                                                </a>
                                                <form
                                                    id="{{ $item->id }}"
                                                    action="{{ route("admin.users.destroy", $item->id) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method("DELETE")
                                                    <button
                                                        type="button"
                                                        onclick="confirmDelete('{{ $item->id }}')"
                                                        class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-red-500 rounded-lg shadow-theme-xs hover:bg-red-600"
                                                    >
                                                        <i class="fa-regular fa-trash-can"></i>
                                                        <p class="hidden text-theme-sm md:block">Hapus</p>
                                                    </button>
                                                </form>
                                                <a
                                                    @click="loaded = true"
                                                    href="{{ route("admin.export.users.single", $item->id) }}"
                                                    class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-white transition bg-gray-500 rounded-lg shadow-theme-xs hover:bg-gray-600"
                                                >
                                                    <i class="fa-solid fa-file-export"></i>
                                                    <p class="hidden text-theme-sm md:block">Export</p>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-4 text-center text-gray-500 text-theme-xs">
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

    <script>
        function filterForm() {
            return {
                // === INIT OLD VALUE ===
                zone_id: '{{ isset($filters["zone_id"]) ? $filters["zone_id"] : "" }}',
                village_id: '{{ isset($filters["village_id"]) ? $filters["village_id"] : "" }}',
                group_id: '{{ isset($filters["group_id"]) ? $filters["group_id"] : "" }}',

                villages: [],
                groups: [],

                async init() {
                    if (this.zone_id) {
                        await this.getVillages(this.zone_id, false);

                        await this.getGroups(this.village_id);
                    }

                    if (this.village_id) {
                        await this.getGroups(this.village_id);
                    }
                },

                async getVillages(zone_id, reset = true) {
                    if (reset) {
                        this.village_id = '';
                        this.group_id = '';
                        this.groups = [];
                    }

                    const res = await fetch(`/api/get-villages/${zone_id}`);
                    this.villages = await res.json();
                    this.village_id = this.villages[0]?.id || '';
                    await this.getGroups(this.village_id);
                },

                async getGroups(village_id) {
                    this.group_id = '';

                    const res = await fetch(`/api/get-groups/${village_id}`);
                    this.groups = await res.json();
                    this.group_id = this.groups[0]?.id || '';
                },
            };
        }
    </script>
@endpush
