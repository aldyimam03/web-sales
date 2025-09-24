<x-app>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Tambah Penjualan</h1>
            <p class="text-slate-600">Lengkapi form di bawah untuk membuat transaksi penjualan</p>
        </div>

        <!-- Flash Messages -->
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg fade-in">
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        @endif
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg fade-in">
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 fade-in">
            <form action="{{ route('sales.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Kode Penjualan -->
                <div class="form-group">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Penjualan</label>
                    <input type="text" name="kode_penjualan" value="{{ $kodePenjualan ?? 'AUTO' }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-600"
                        readonly>
                </div>

                <!-- Tanggal -->
                <div class="form-group">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Penjualan</label>
                    <input type="date" name="tanggal_penjualan" value="{{ now()->toDateString() }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl ">
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <input type="text" name="status" value="Belum Dibayar"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-600"
                        readonly>
                </div>

                <!-- Items Table -->
                @include('sales.partials.items-table', ['items' => $items])

                <!-- Total Penjualan -->
                <div class="form-group">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Total Penjualan</label>
                    <input type="text" id="grand-total" name="total_harga" readonly
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-700 font-semibold">
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <a href="{{ route('sales.index') }}"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan Penjualan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app>
