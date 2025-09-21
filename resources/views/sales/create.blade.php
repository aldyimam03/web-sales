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
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500">
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
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('sales.index') }}"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200">
                        Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg">
                        Simpan Penjualan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app>
