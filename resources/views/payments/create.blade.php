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

    <div class="p-6 fade-in">
        <div class="max-w-4xl mx-auto">


            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-slate-800 mb-2">Tambah Pembayaran</h1>
                <p class="text-slate-600">Perbarui transaksi pembayaran</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 fade-in">

                <form action="{{ route('payments.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Pilih Penjualan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Penjualan</label>
                        <select name="sale_id" id="sale-select"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-600" required>
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
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Total Penjualan</label>
                        <input type="text" id="total-penjualan" readonly
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-600">
                    </div>

                    <!-- Sudah Terbayar -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sudah Terbayar</label>
                        <input type="text" id="sudah-terbayar" readonly
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-600">
                    </div>

                    <!-- Sisa -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sisa Pembayaran</label>
                        <input type="text" id="sisa-bayar" readonly
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-600">
                    </div>

                    <!-- Tanggal Bayar -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" value="{{ now()->toDateString() }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-600"
                            required>
                    </div>

                    <!-- Jumlah Bayar -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Dibayar</label>
                        <input type="number" step="0.01" name="jumlah"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-600 no-spinner"
                            required>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan</label>
                        <textarea name="catatan" class="w-full border rounded-lg px-3 py-2"></textarea>
                    </div>

                    <!-- Tombol -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <a href="{{ route('payments.index') }}"
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
