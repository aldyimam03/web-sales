<x-app :pageTitle="$pageTitle">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 mb-2">Daftar Penjualan</h1>
                <p class="text-slate-600">List semua transaksi penjualan di sistem</p>
            </div>
            <a href="{{ route('sales.create') }}"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Penjualan
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg fade-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg fade-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Filter -->
        <form method="GET" action="{{ route('sales.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Awal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="px-4 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="px-4 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="px-6 py-2 bg-slate-600 text-white rounded-xl hover:bg-slate-700 shadow transition">
                    Filter
                </button>
                <a href="{{ route('sales.index') }}"
                    class="px-6 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 shadow transition">
                    Reset
                </a>
            </div>
        </form>


        <!-- DataTable -->
        <div class="bg-white rounded-2xl shadow-xl p-6 fade-in">
            <div class="overflow-x-auto">
                <table id="sales-table" class="min-w-full text-sm text-slate-950">
                    <thead>
                        <tr class="bg-slate-200 text-slate-700 uppercase text-xs">
                            <th class="px-6 py-3 text-center">No.</th>
                            <th class="px-6 py-3 text-center">Kode</th>
                            <th class="px-6 py-3 text-center">Tanggal</th>
                            <th class="px-6 py-3 text-center">Total</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($sales as $sale)
                            <tr class="hover:bg-slate-100 transition">
                                <td class="px-6 py-4 text-center">
                                    {{ $loop->iteration + $sales->perPage() * ($sales->currentPage() - 1) }}</td>
                                <td class="px-6 py-4 text-center">{{ $sale->kode_penjualan }}</td>
                                <td class="px-6 py-4 text-center">{{ $sale->tanggal_penjualan->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right">Rp
                                    {{ number_format($sale->total_harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-3 py-1 rounded-lg text-xs font-medium  
                                        @if ($sale->status == 'Belum Dibayar') bg-red-100 text-red-800
                                        @elseif ($sale->status == 'Sudah Dibayar') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800 @endif
                                        ">
                                        {{ $sale->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ route('sales.show', $sale->id) }}"
                                        class="px-3 py-1 bg-slate-500 text-white rounded-lg hover:bg-slate-600">Detail</a>
                                    @if ($sale->status == 'Belum Dibayar')
                                        <a href="{{ route('sales.edit', $sale->id) }}"
                                            class="px-3 py-1 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500">Edit</a>
                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus penjualan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-slate-500 italic">Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-6">
        {{ $sales->links() }}
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

        <style>
            /* Hilangkan garis bawaan */
            table.dataTable.no-footer {
                border-bottom: none;
            }

            table.dataTable thead th {
                border-bottom: none !important;
            }

            /* Header styling */
            #sales-table thead th {
                text-align: center !important;
                font-weight: 700;
                background-color: #e2e8f0;
                /* slate-200 */
                color: #334155;
                /* slate-700 */
                padding: 12px;
            }

            /* Isi tabel */
            /* #users-table tbody td {
                    text-align: center !important;
                    vertical-align: middle !important;
                    padding: 12px;
                } */

            /* Search box styling */
            .dataTables_filter {
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .dataTables_filter label {
                font-weight: 600;
                color: #334155;
                /* slate-700 */
            }

            .dataTables_filter input {
                padding: 0.5rem 1rem;
                border: 1px solid #cbd5e1;
                /* slate-300 */
                border-radius: 0.75rem;
                /* rounded-xl */
                outline: none;
                transition: all 0.2s;
            }
            .dataTables_wrapper .dataTables_filter {
                float: left !important;
                text-align: left !important;
            }
        </style>

        <script>
            $(document).ready(function() {
                var columnCount = $('#sales-table thead tr th').length;

                $('#sales-table').DataTable({
                    paging: false,
                    info: false,
                    searching: true,
                    ordering: true,
                    language: {
                        search: "Cari : ",
                        searchPlaceholder: "Ketik untuk mencari..."
                    },
                    columnDefs: columnCount > 5 ? [{
                        "orderable": false,
                        "targets": -1
                    }] : []
                });
            });
        </script>
    @endpush
</x-app>
