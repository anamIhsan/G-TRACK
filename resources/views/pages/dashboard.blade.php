@extends('layouts.app')
@section('title', 'Dashboard Statistik')
@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Legend -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                    <span class="text-gray-700 text-sm font-medium">Laki-laki</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-pink-500"></div>
                    <span class="text-gray-700 text-sm font-medium">Perempuan</span>
                </div>
            </div>
        </div>

        <!-- Pie Charts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <!-- Total Semua Generus -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-gray-600 text-sm font-medium text-center mb-4">Total Semua Generus</h3>
                <div class="relative w-48 h-48 mx-auto">
                    <canvas id="chartSemuaGenerus"></canvas>
                </div>
            </div>

            <!-- Total Caberawit -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-gray-600 text-sm font-medium text-center mb-4">Total Caberawit</h3>
                <div class="relative w-48 h-48 mx-auto">
                    <canvas id="chartCaberawit"></canvas>
                </div>
            </div>

            <!-- Total Pra Remaja -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-gray-600 text-sm font-medium text-center mb-4">Total Pra Remaja</h3>
                <div class="relative w-48 h-48 mx-auto">
                    <canvas id="chartPraRemaja"></canvas>
                </div>
            </div>

            <!-- Total Remaja -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-gray-600 text-sm font-medium text-center mb-4">Total Remaja</h3>
                <div class="relative w-48 h-48 mx-auto">
                    <canvas id="chartRemaja"></canvas>
                </div>
            </div>

            <!-- Total Pra Nikah -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-gray-600 text-sm font-medium text-center mb-4">Total Pra Nikah</h3>
                <div class="relative w-48 h-48 mx-auto">
                    <canvas id="chartPraNikah"></canvas>
                </div>
            </div>
        </div>

        <!-- Tingkat Kekhataman Section -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Tingkat Kekhataman</h2>
        </div>

        <!-- Kekhataman Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Qur'an -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-gray-700 font-semibold mb-4 pb-3 border-b">Qur'an</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 100%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">11 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 76% - 99%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">0 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 51% - 75%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">1 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 26% - 50%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">1 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 0% - 25%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">0 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 0%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">50 generus</span>
                    </div>
                </div>
            </div>

            <!-- K. Sholah -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-gray-700 font-semibold mb-4 pb-3 border-b">K. Sholah</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 100%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">9 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 76% - 99%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">0 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 51% - 75%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">0 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 26% - 50%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">0 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 0% - 25%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">0 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 0%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">54 generus</span>
                    </div>
                </div>
            </div>

            <!-- K. Nawafil -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-gray-700 font-semibold mb-4 pb-3 border-b">K. Nawafil</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 100%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">8 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 76% - 99%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">1 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 51% - 75%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">0 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 26% - 50%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">0 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 0% - 25%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">0 generus</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 0%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">54 generus</span>
                    </div>
                </div>
            </div>

            <!-- K. Da'wat -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-gray-700 font-semibold mb-4 pb-3 border-b">K. Da'wat</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 100%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">8 generus</span>
                    </div>
                </div>
            </div>

            <!-- K. Jama'iz -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-gray-700 font-semibold mb-4 pb-3 border-b">K. Jama'iz</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 100%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">8 generus</span>
                    </div>
                </div>
            </div>

            <!-- K. Jannah Wannar -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-gray-700 font-semibold mb-4 pb-3 border-b">K. Jannah Wannar</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Khatam 100%</span>
                        <span class="text-gray-600 text-sm">:</span>
                        <span class="text-gray-800 font-medium">9 generus</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Chart Configuration
            const chartConfig = {
                type: 'doughnut',
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    cutout: '0%'
                }
            };

            // Data for charts
            const chartsData = {
                semuaGenerus: {
                    male: 26,
                    female: 37
                },
                caberawit: {
                    male: 4,
                    female: 7
                },
                praRemaja: {
                    male: 0,
                    female: 4
                },
                remaja: {
                    male: 10,
                    female: 11
                },
                praNikah: {
                    male: 12,
                    female: 14
                }
            };

            // Function to create chart
            function createChart(canvasId, data) {
                const ctx = document.getElementById(canvasId).getContext('2d');
                return new Chart(ctx, {
                    ...chartConfig,
                    data: {
                        labels: ['Laki-laki', 'Perempuan'],
                        datasets: [{
                            data: [data.male, data.female],
                            backgroundColor: ['#3B82F6', '#EC4899'],
                            borderWidth: 0
                        }]
                    },
                    plugins: [{
                        afterDatasetsDraw(chart) {
                            const {
                                ctx,
                                chartArea: {
                                    width,
                                    height
                                }
                            } = chart;
                            chart.data.datasets.forEach((dataset, i) => {
                                const meta = chart.getDatasetMeta(i);
                                meta.data.forEach((element, index) => {
                                    const {
                                        x,
                                        y
                                    } = element.tooltipPosition();
                                    const value = dataset.data[index];

                                    ctx.fillStyle = '#fff';
                                    ctx.font = 'bold 24px sans-serif';
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'middle';
                                    ctx.fillText(value, x, y);
                                });
                            });
                        }
                    }]
                });
            }

            // Initialize all charts
            document.addEventListener('DOMContentLoaded', function() {
                createChart('chartSemuaGenerus', chartsData.semuaGenerus);
                createChart('chartCaberawit', chartsData.caberawit);
                createChart('chartPraRemaja', chartsData.praRemaja);
                createChart('chartRemaja', chartsData.remaja);
                createChart('chartPraNikah', chartsData.praNikah);
            });
        </script>
    @endpush
@endsection
