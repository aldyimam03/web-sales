<x-app>

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
    
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Edit Penjualan</h1>
            <p class="text-slate-600">Perbarui transaksi penjualan</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 fade-in">
            <form action="{{ route('sales.update', $sale->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold">Kode</label>
                    <input type="text" value="{{ $sale->kode_penjualan }}"
                        class="w-full px-4 py-3 border rounded-xl bg-slate-100" readonly>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Tanggal</label>
                    <input type="date" name="tanggal_penjualan"
                        value="{{ $sale->tanggal_penjualan->toDateString() }}"
                        class="w-full px-4 py-3 border rounded-xl">
                </div>

                <div>
                    <label class="block text-sm font-semibold">Status</label>
                    <input type="text" value="{{ $sale->status }}"
                        class="w-full px-4 py-3 border rounded-xl bg-slate-100" readonly>
                </div>

                @if ($sale->status == 'Belum Dibayar')
                    @include('sales.partials.items-table', ['items' => $items, 'sale' => $sale])

                    <!-- Total Penjualan -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Total Penjualan</label>
                        <input type="text" id="grand-total" name="total_harga" value="{{ $sale->total_harga }}"
                            readonly
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-700 font-semibold">
                    </div>
                @else
                    <p class="text-red-600">Penjualan ini sudah dibayar dan tidak bisa diubah.</p>
                @endif

                <div class="flex justify-between pt-6">
                    <a href="{{ route('sales.index') }}" class="px-6 py-3 bg-slate-100 rounded-xl">Kembali</a>
                    @if ($sale->status == 'Belum Dibayar')
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">Update</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-app>
