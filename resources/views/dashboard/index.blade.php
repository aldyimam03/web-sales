<x-app>

    <div class="space-y-8">

        @stack('scripts')

        <!-- Filter -->
        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Awal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="px-4 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="px-4 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="px-6 py-2 bg-slate-600 text-white rounded-xl hover:bg-slate-700 shadow transition">
                    Filter
                </button>
                <a href="{{ route('dashboard') }}"
                    class="px-6 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 shadow transition">
                    Reset
                </a>
            </div>
        </form>

        {{-- Widgets --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 bg-white rounded-2xl shadow card-hover">
                <h3 class="text-sm text-center font-medium text-slate-500">Jumlah Transaksi</h3>
                <p class="mt-3 text-3xl text-center font-bold text-slate-800">{{ $totalTransactions }}</p>
            </div>
            <div class="p-6 bg-white rounded-2xl shadow card-hover">
                <h3 class="text-sm text-center font-medium text-slate-500">Total Penjualan</h3>
                <p class="mt-3 text-3xl text-center font-bold text-slate-800">Rp
                    {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="p-6 bg-white rounded-2xl shadow card-hover">
                <h3 class="text-sm text-center font-medium text-slate-500">Total Qty Item Terjual</h3>
                <p class="mt-3 text-3xl text-center font-bold text-slate-800">{{ $totalQty }}</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 bg-white rounded-2xl shadow card-hover">
                <h3 class="text-lg text-center font-semibold text-slate-700 mb-4">Penjualan per Bulan</h3>
                <canvas id="revenueChart"></canvas>
            </div>
            <div class="p-6 bg-white rounded-2xl shadow card-hover">
                <h3 class="text-lg text-center font-semibold text-slate-700 mb-4">Qty Item Terjual per Item</h3>
                <canvas id="qtyChart" class="w-full h-64"></canvas>
            </div>
        </div>
    </div>

    <style>
        canvas {
            max-width: 100%;
            height: 300px;
        }

        .card-hover {
            transition: transform 0.2s ease-in-out;
        }

        .card-hover:hover {
            transform: translateY(-2px);
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Revenue Chart
                const revenueCtx = document.getElementById('revenueChart');
                if (revenueCtx) {
                    try {
                        new Chart(revenueCtx, {
                            type: 'bar',
                            data: {
                                labels: @json($revenueByMonth['labels']),
                                datasets: [{
                                    label: 'Total Penjualan (Rp)',
                                    data: @json($revenueByMonth['data']),
                                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                                    borderColor: 'rgba(59, 130, 246, 1)',
                                    borderWidth: 1,
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return 'Rp ' + value.toLocaleString();
                                            }
                                        }
                                    }
                                }
                            }
                        });
                        console.log('Revenue chart loaded successfully');
                    } catch (error) {
                        console.error('Error loading revenue chart:', error);
                    }
                }

                // Qty Chart
                const qtyCtx = document.getElementById('qtyChart');
                if (qtyCtx) {
                    try {
                        new Chart(qtyCtx, {
                            type: 'bar',
                            data: {
                                labels: @json($qtyByItem['labels']),
                                datasets: [{
                                    label: 'Qty Terjual',
                                    data: @json($qtyByItem['data']),
                                    backgroundColor: 'rgba(16, 185, 129, 0.6)',
                                    borderColor: 'rgba(16, 185, 129, 1)',
                                    borderWidth: 1,
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                indexAxis: 'y', 
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1 
                                        }
                                    }
                                }
                            }
                        });
                        console.log('Qty chart loaded successfully');
                    } catch (error) {
                        console.error('Error loading qty chart:', error);
                    }
                }
            });
        </script>
    @endpush
</x-app>