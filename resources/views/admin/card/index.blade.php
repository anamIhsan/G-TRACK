@extends("layouts.app")

@section("title", "ADMIN | Cetak Kartu")

@section("content")
    <div class="col-span-12" x-data="{ showFilterCollapse: false }">
        <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Cetak Kartu</h3>
            <div class="flex flex-row flex-wrap items-center gap-3">
                @if (in_array($authData->role, ["MASTER", "ADMIN_DAERAH"]))
                    <a
                        href="{{ route("admin.cards.twibbon.create") }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 hover:bg-brand-600 shadow-theme-xs"
                    >
                        <i class="fa-regular fa-image"></i>
                        Ubah Twibbon
                    </a>
                @endif

                <a
                    href="{{ isset($twibbon) && $twibbon != null ? route("admin.cards.twibbon.download.all", isset($filters) ? $filters : "") : "#" }}"
                    class="{{ isset($twibbon) && $twibbon != null ? "bg-brand-500 hover:bg-brand-600" : "bg-gray-500 cursor-not-allowed" }} inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg shadow-theme-xs"
                >
                    <i class="fa-solid fa-download"></i>
                    Unduh Semua
                </a>

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
                    href="{{ route("admin.cards.index") }}"
                >
                    See all
                </a>
            </div>
        </div>

        <div x-show="showFilterCollapse" class="mb-5">
            <form x-data="filterForm()" action="{{ route("admin.cards.index") }}" method="GET">
                <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Filter Kartu</h2>
                </div>

                <div class="grid grid-cols-1 gap-3 mt-5 space-y-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Daerah</label>
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
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Desa</label>
                        <select
                            name="village_id"
                            class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                            x-model="village_id"
                            @change="getGroups(village_id)"
                        >
                            <option selected value="">Semua Desa</option>
                            <template x-for="v in villages" :key="v.id">
                                <option :value="v.id" x-selected="v.id == village_id" x-text="v.nama"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Kelompok</label>
                        <select
                            name="group_id"
                            class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                            x-model="group_id"
                        >
                            <option selected value="">Semua Kelompok</option>
                            <template x-for="g in groups" :key="g.id">
                                <option :value="g.id" x-selected="g.id == group_id" x-text="g.nama"></option>
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
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Minat</label>
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
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Sub Minat</label>
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
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Komunitas</label>
                        <select
                            name="community_id"
                            class="w-full rounded-lg border border-gray-300 bg-white/5 p-2.5 text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                        >
                            <option selected value="">Semua Komunitas</option>
                            @foreach ($communities as $community)
                                <option
                                    {{ isset($filters["community_id"]) ? ($filters["community_id"] == $village->id ? "selected" : "") : "" }}
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

        <div class="grid grid-cols-1 gap-3 md:grid-cols-3 lg:grid-cols-5">
            @if (isset($twibbon) && $twibbon != null)
                @forelse ($users as $item)
                    <div
                        x-data
                        x-init="
                            new QRCode($refs.qrcode, {
                                text: '{{ $item->nfc_id }}',
                                width: 180,
                                height: 180,
                            })
                        "
                        class="mb-3 overflow-hidden border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5"
                    >
                        {{--
                            <div
                            class="flex items-center justify-between w-full px-5 py-4 text-left transition bg-white dark:bg-transparent hover:bg-gray-50 dark:hover:bg-white/5"
                            >
                            <div class="flex items-center gap-3">
                            <div class="flex flex-col">
                            <p class="font-medium text-gray-800 dark:text-white/90">{{ $item->nama }}</p>
                            </div>
                            </div>
                            </div>
                        --}}

                        <div
                            class="px-5 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30"
                        >
                            <div class="flex flex-col">
                                <div class="relative">
                                    <img
                                        src="{{ isset($item->twibbon_user) ? $item->twibbon_user : "" }}"
                                        alt="Twibbon"
                                    />
                                </div>
                                <div class="flex flex-row items-center gap-3 mt-4">
                                    <a
                                        href="{{ route("admin.cards.twibbon.download", $item->id) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 hover:bg-brand-600 shadow-theme-xs"
                                    >
                                        <i class="fa-solid fa-download"></i>
                                        Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="p-6 text-center border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5"
                    >
                        <p class="text-gray-500 dark:text-gray-400">Belum ada data pengguna.</p>
                    </div>
                @endforelse
            @else
                <div class="p-6 text-center border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5">
                    <p class="text-gray-500 dark:text-gray-400">Belum ada backgroud.</p>
                </div>
            @endif
        </div>
        @if (isset($twibbon) && $twibbon != null && $users->count() > 0)
            <x-pagination :data="$users" />
        @endif
    </div>
@endsection

@push("scripts")
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
