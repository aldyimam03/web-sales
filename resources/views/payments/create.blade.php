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

    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Tambah Pembayaran</h1>

        <form action="{{ route('payments.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Pilih Penjualan -->
            <div>
                <label class="block text-sm font-semibold mb-2">Pilih Penjualan</label>
                <select name="sale_id" id="sale-select" class="w-full border rounded-lg px-3 py-2" required>
                    @foreach ($sales as $sale)
                        <option value="{{ $sale->id }}" data-total="{{ $sale->total_harga }}"
                            data-paid="{{ $sale->total_dibayar }}">
                            {{ $sale->kode_penjualan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Total Penjualan -->
            <div>
                <label class="block text-sm font-semibold mb-2">Total Penjualan</label>
                <input type="text" id="total-penjualan" readonly
                    class="w-full border rounded-lg px-3 py-2 bg-slate-100 text-slate-700 font-medium">
            </div>

            <!-- Sudah Terbayar -->
            <div>
                <label class="block text-sm font-semibold mb-2">Sudah Terbayar</label>
                <input type="text" id="sudah-terbayar" readonly
                    class="w-full border rounded-lg px-3 py-2 bg-slate-100 text-slate-700 font-medium">
            </div>

            <!-- Sisa -->
            <div>
                <label class="block text-sm font-semibold mb-2">Sisa Pembayaran</label>
                <input type="text" id="sisa-bayar" readonly
                    class="w-full border rounded-lg px-3 py-2 bg-slate-100 text-slate-700 font-medium">
            </div>

            <!-- Tanggal Bayar -->
            <div>
                <label class="block text-sm font-semibold mb-2">Tanggal Bayar</label>
                <input type="date" name="tanggal_bayar" value="{{ now()->toDateString() }}"
                    class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <!-- Jumlah Bayar -->
            <div>
                <label class="block text-sm font-semibold mb-2">Jumlah Dibayar</label>
                <input type="number" step="0.01" name="jumlah" class="w-full border rounded-lg px-3 py-2 no-spinner" required>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-semibold mb-2">Catatan</label>
                <textarea name="catatan" class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>

            <!-- Tombol -->
            <div class="flex justify-between">
                <a href="{{ route('payments.index') }}" class="px-6 py-3 bg-slate-100 rounded-xl">Kembali</a>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('sale-select');
            const totalInput = document.getElementById('total-penjualan');
            const paidInput = document.getElementById('sudah-terbayar');
            const sisaInput = document.getElementById('sisa-bayar');

            function updateInfo() {
                const option = select.selectedOptions[0];
                const total = parseFloat(option.dataset.total);
                const paid = parseFloat(option.dataset.paid);
                const sisa = total - paid;

                totalInput.value = `Rp ${total.toLocaleString('id-ID')}`;
                paidInput.value = `Rp ${paid.toLocaleString('id-ID')}`;
                sisaInput.value = `Rp ${sisa.toLocaleString('id-ID')}`;
            }

            select.addEventListener('change', updateInfo);
            updateInfo();
        });
    </script>
</x-app>
