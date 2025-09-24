<x-app :pageTitle="$pageTitle">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 mb-2">Daftar User</h1>
                <p class="text-slate-600">List user yang terdaftar di sistem</p>
            </div>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </a>
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
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- DataTable -->
        <div class="bg-white rounded-2xl shadow-xl p-6 fade-in">
            <div class="overflow-x-auto">
                <table id="users-table" class="min-w-full text-sm text-slate-950">
                    <thead>
                        <tr class="rounded-2xl bg-slate-200 text-slate-700 uppercase text-xs">
                            <th class="px-6 py-3 text-center">No.</th>
                            <th class="px-6 py-3 text-center">Nama</th>
                            <th class="px-6 py-3 text-center">Email</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($users as $user)
                            <tr class="hover:bg-slate-100 transition">
                                <td class="px-6 py-4 text-center">
                                    {{ $loop->iteration + $users->perPage() * ($users->currentPage() - 1) }}
                                </td>
                                <td class="px-6 py-4 text-left">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-left">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="px-3 py-1 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
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
            #users-table thead th {
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
                var columnCount = $('#users-table thead tr th').length;

                $('#users-table').DataTable({
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
