@extends("layouts.app")

@section("title", "Dashboard")

@section("content")
    <div class="px-4 py-4 container-fluid">
        <!-- Legend -->
        @if (in_array($authData->role, ["MASTER", "ADMIN_DAERAH", "ADMIN_DESA", "ADMIN_KELOMPOK"]))
            <div class="p-4 mb-6 bg-white rounded-lg shadow-sm">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Laki-laki</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-pink-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Perempuan</span>
                    </div>
                </div>
            </div>

            <!-- Pie Charts Grid -->
            <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Total Semua -->
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <h3 class="mb-4 text-sm font-medium text-center text-gray-600">Total Semua</h3>
                    <div class="relative w-48 h-48 mx-auto">
                        <canvas id="chartSemuaUser"></canvas>
                    </div>
                </div>

                @foreach ($age_categories as $age_category)
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <h3 class="mb-4 text-sm font-medium text-center text-gray-600">
                            Total {{ $age_category->nama }}
                        </h3>
                        <div class="relative w-48 h-48 mx-auto">
                            <canvas id="chart{{ $age_category->slug }}"></canvas>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Kehadiran -->
            <div class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
                {{-- HEADER --}}
                <div class="flex flex-col gap-2 mb-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Chart Kehadiran</h3>
                        <p class="text-sm text-gray-500">{{ $startDate }} s/d {{ $endDate }}</p>
                    </div>
                </div>

                {{-- FILTER --}}
                <form
                    method="GET"
                    action="{{ route("dashboard") }}"
                    class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4"
                >
                    {{-- DARI TANGGAL --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Dari Tanggal</label>
                        <div class="relative">
                            <input
                                name="start_date"
                                type="date"
                                value="{{ request("start_date", $startDate) }}"
                                onclick="this.showPicker()"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 focus:ring-3 focus:outline-hidden"
                            />
                            <span class="absolute text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3">
                                {{-- ICON --}}
                                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984H5.91659V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415Z"
                                    />
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- SAMPAI TANGGAL --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                        <div class="relative">
                            <input
                                name="end_date"
                                type="date"
                                value="{{ request("end_date", $endDate) }}"
                                onclick="this.showPicker()"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 focus:ring-3 focus:outline-hidden"
                            />
                            <span class="absolute text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3">
                                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984H5.91659V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415Z"
                                    />
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="w-full text-sm font-medium text-white transition rounded-lg h-11 bg-brand-500 hover:bg-brand-600 shadow-theme-xs"
                        >
                            Filter Kehadiran
                        </button>
                    </div>
                </form>

                {{-- CHART --}}
                <div class="relative w-full h-72">
                    <canvas id="chartKehadiran"></canvas>
                </div>
            </div>

            <!-- Tingkat Kekhataman Section -->
            <div class="mb-6">
                <h2 class="mb-4 text-xl font-semibold text-gray-700">Tingkat Kekhataman</h2>
            </div>

            <!-- Kekhataman Cards Grid -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($metafieldStats as $meta)
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <h3 class="pb-3 mb-4 font-semibold text-gray-700 border-b">{{ $meta["label"] }}</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-gray-600">Progres 70–100%</span>
                                <span class="font-medium text-gray-800">{{ $meta["stats"]["70_100"] }} user</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-gray-600">Progres 40–69%</span>
                                <span class="font-medium text-gray-800">{{ $meta["stats"]["40_69"] }} user</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-gray-600">Progres 0–39%</span>
                                <span class="font-medium text-gray-800">{{ $meta["stats"]["0_39"] }} user</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="grid grid-cols-1">
                <div class="p-6 mb-6 bg-white rounded-lg shadow-sm">
                    <h3 class="pb-3 mb-4 font-semibold text-gray-700 border-b">Tingkat Kekhataman</h3>
                    <div class="w-full space-y-3">
                        @php
                            $metafieldLevelCount = count($metafieldLevels);
                        @endphp

                        <div
                            class="grid grid-cols-1 gap-10 {{ isset($metafieldLevels) ? ($metafieldLevelCount === 1 ? "md:grid-cols-1 lg:grid-cols-1" : ($metafieldLevelCount >= 2 ? "md:grid-cols-2 lg:grid-cols-2" : "md:grid-cols-1 lg:grid-cols-1")) : "md:grid-cols-1 lg:grid-cols-1" }}"
                        >
                            @php
                                $level = $authData->level;
                            @endphp

                            @foreach ($metafieldLevels as $metafieldLevel)
                                @if (isset($level))
                                    @php
                                        $metaFieldLevelUser = $level
                                            ->where("metafield_level_id", $metafieldLevel->id)
                                            ->first();
                                    @endphp

                                    <div
                                        class="flex items-center w-full justify-between px-3 py-2 border {{ isset($metaFieldLevelUser->value) ? ($metaFieldLevelUser->value >= 70 ? "bg-green-100" : ($metaFieldLevelUser->value >= 40 ? "bg-yellow-100" : ($metaFieldLevelUser->value > 0 ? "bg-red-100" : ""))) : "" }} text-gray-500 dark:text-gray-400"
                                    >
                                        <span class="font-medium text-gray-800">
                                            {{ $metafieldLevel->field_name }}
                                        </span>
                                        <span class="text-sm text-gray-600">:</span>
                                        <span class="text-sm text-gray-600">
                                            Khatam {{ $metaFieldLevelUser->value ?? 0 }}%
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end mt-3">
                        <a class="text-sm text-blue-500 underline" href="{{ route("user.user_levels.index") }}">
                            See all
                        </a>
                    </div>
                </div>

                <div class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
                    {{-- HEADER --}}
                    <div class="flex flex-col gap-2 mb-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Chart Kehadiran</h3>
                            <p class="text-sm text-gray-500">{{ $startDate }} s/d {{ $endDate }}</p>
                        </div>
                    </div>

                    {{-- FILTER --}}
                    <form
                        method="GET"
                        action="{{ route("dashboard") }}"
                        class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4"
                    >
                        {{-- DARI TANGGAL --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Dari Tanggal</label>
                            <input
                                name="start_date"
                                type="date"
                                value="{{ request("start_date", $startDate) }}"
                                onclick="this.showPicker()"
                                class="w-full px-4 text-sm border border-gray-300 rounded-lg h-11"
                            />
                        </div>

                        {{-- SAMPAI TANGGAL --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                            <input
                                name="end_date"
                                type="date"
                                value="{{ request("end_date", $endDate) }}"
                                onclick="this.showPicker()"
                                class="w-full px-4 text-sm border border-gray-300 rounded-lg h-11"
                            />
                        </div>

                        {{-- BUTTON --}}
                        <div class="flex items-end">
                            <button
                                type="submit"
                                class="w-full text-sm font-medium text-white rounded-lg h-11 bg-brand-500"
                            >
                                Filter Kehadiran
                            </button>
                        </div>
                    </form>

                    {{-- CHART --}}
                    <div class="relative w-full h-72">
                        <canvas id="chartKehadiran"></canvas>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push("scripts")
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const age_categories = @json($age_categories);
        const usersPR = @json($usersPR);
        const usersLK = @json($usersLK);
        const kehadiran = @json($kehadiran);

        /* ===============================
         * PIE CHART CONFIG
         * =============================== */
        const chartConfig = {
            type: 'doughnut',
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: true,
                    },
                },
                cutout: '0%',
            },
        };

        function createPieChart(id, data) {
            const ctx = document.getElementById(id);
            if (!ctx) return;

            const male = data?.male ?? 0;
            const female = data?.female ?? 0;
            const fixedData = male + female === 0 ? [0, 0, 0] : [male, female];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Laki-laki', 'Perempuan'],
                    datasets: [
                        {
                            data: fixedData,
                            backgroundColor: ['#3B82F6', '#F472B6', '#fff'],
                            borderWidth: 0,
                        },
                    ],
                },
                options: chartConfig.options,
            });
        }

        /* ===============================
         * BAR CHART KEHADIRAN
         * =============================== */
        function createKehadiranChart() {
            const ctx = document.getElementById('chartKehadiran');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['HADIR', 'IZIN', 'TERLAMBAT', 'SAKIT'],
                    datasets: [
                        {
                            label: 'Total Kehadiran',
                            data: [
                                kehadiran.hadir ?? 0,
                                kehadiran.izin ?? 0,
                                kehadiran.terlambat ?? 0,
                                kehadiran.sakit ?? 0,
                            ],
                            backgroundColor: [
                                '#22C55E', // HADIR
                                '#3B82F6', // IZIN
                                '#F59E0B', // TERLAMBAT
                                '#EF4444', // SAKIT
                            ],
                            borderRadius: 8,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            enabled: true,
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                            },
                        },
                    },
                },
            });
        }

        /* ===============================
         * INIT ALL CHART
         * =============================== */
        document.addEventListener('DOMContentLoaded', function () {
            const totalLK = Array.isArray(usersLK) ? usersLK.length : Object.keys(usersLK).length;

            const totalPR = Array.isArray(usersPR) ? usersPR.length : Object.keys(usersPR).length;

            // Pie total user
            createPieChart('chartSemuaUser', {
                male: totalLK,
                female: totalPR,
            });

            // Pie per kategori umur
            age_categories?.forEach((age) => {
                const lk = age.users?.filter((u) => u.kelamin === 'LK').length || 0;
                const pr = age.users?.filter((u) => u.kelamin === 'PR').length || 0;

                createPieChart(`chart${age.slug}`, {
                    male: lk,
                    female: pr,
                });
            });

            // Bar kehadiran
            createKehadiranChart();
        });
    </script>
@endpush
