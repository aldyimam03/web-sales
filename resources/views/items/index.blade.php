<x-app>
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 mb-2">Daftar Item</h1>
                <p class="text-slate-600">List item yang tersedia di sistem</p>
            </div>

            @can('item.create')
                <a href="{{ route('items.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Item
                </a>
            @endcan
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg fade-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- DataTable -->
        <div class="bg-white rounded-2xl shadow-xl p-6 fade-in">
            <div class="overflow-x-auto">
                <table id="items-table" class="min-w-full text-sm text-slate-950">
                    <thead>
                        <tr class="rounded-2xl bg-slate-200 text-slate-700 uppercase text-xs">
                            <th class="px-6 py-3 text-center">No.</th>
                            <th class="px-6 py-3 text-center">Kode</th>
                            <th class="px-6 py-3 text-center">Nama</th>
                            <th class="px-6 py-3 text-center">Harga</th>
                            <th class="px-6 py-3 text-center">Gambar</th>

                            @if (auth()->user()->can('item.update') || auth()->user()->can('item.delete'))
                                <th class="px-6 py-3 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-center">
                        @foreach ($items as $item)
                            <tr class="hover:bg-slate-100 transition">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration + $items->perPage() * ($items->currentPage() - 1) }}
                                </td>
                                <td class="px-6 py-4">{{ $item->kode }}</td>
                                <td class="px-6 py-4">{{ $item->nama }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 flex justify-center">
                                    @if ($item->gambar)
                                        <img src="{{ $item->gambar_url }}"
                                            class="h-12 w-12 object-cover rounded-lg border">
                                    @else
                                        <span class="text-slate-400 italic">Tidak ada</span>
                                    @endif
                                </td>
                                @if (auth()->user()->can('item.update') || auth()->user()->can('item.delete'))
                                    <td class="px-6 py-4 space-x-2">
                                        @can('item.update')
                                            <a href="{{ route('items.edit', $item->id) }}"
                                                class="px-3 py-1 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500">Edit</a>
                                        @endcan

                                        @can('item.delete')
                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600">Delete</button>
                                            </form>
                                        @endcan
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Laravel -->
        <div class="mt-6">
            {{ $items->links() }}
        </div>
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
            #items-table thead th {
                text-align: center !important;
                font-weight: 700;
                background-color: #e2e8f0;
                /* slate-200 */
                color: #334155;
                /* slate-700 */
                padding: 12px;
            }

            /* Isi tabel */
            #items-table tbody td {
                text-align: center !important;
                vertical-align: middle !important;
                padding: 12px;
            }

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

            .dataTables_filter input:focus {
                border-color: #3b82f6;
                /* blue-500 */
                box-shadow: 0 0 0 2px #bfdbfe;
                /* ring-blue-200 */
            }
        </style>

        <script>
            $(document).ready(function() {
                var columnCount = $('#items-table thead tr th').length;

                $('#items-table').DataTable({
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
