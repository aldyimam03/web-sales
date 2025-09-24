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
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Edit Pembayaran</h1>
            <p class="text-slate-600">Perbarui data pembayaran</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 fade-in">
            <form action="{{ route('payments.update', $payment->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Penjualan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Penjualan</label>
                    <input type="text" value="{{ $payment->sale->kode_penjualan }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-600 font-medium" readonly>
                </div>

                <!-- Total Penjualan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Total Penjualan</label>
                    <input type="text" value="Rp {{ number_format($payment->sale->total_harga, 0, ',', '.') }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-600 font-medium" readonly>
                </div>

                <!-- Sudah Terbayar -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Sudah Terbayar</label>
                    @php
                        $sudahTerbayar = $payment->sale->payments()->sum('jumlah');
                    @endphp
                    <input type="text" value="Rp {{ number_format($sudahTerbayar, 0, ',', '.') }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-600 font-medium" readonly>
                </div>

                <!-- Sisa Pembayaran -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Sisa Pembayaran</label>
                    @php
                        $sisa = $payment->sale->total_harga - $sudahTerbayar;
                    @endphp
                    <input type="text" value="Rp {{ number_format($sisa, 0, ',', '.') }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-slate-100 text-slate-600 font-medium" readonly>
                </div>

                <!-- Tanggal Bayar -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" value="{{ $payment->tanggal_bayar->toDateString() }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl" required>
                </div>

                <!-- Jumlah Bayar -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah</label>
                    <input type="number" step="0.01" name="jumlah" value="{{ $payment->jumlah }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl" required>
                    <p class="text-xs text-slate-500 mt-1">Maksimal: Rp {{ number_format($sisa, 0, ',', '.') }}</p>
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan</label>
                    <textarea name="catatan" class="w-full px-4 py-3 border border-slate-300 rounded-xl">{{ $payment->catatan }}</textarea>
                </div>

                <!-- Tombol -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('payments.index') }}"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">
                        Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-amber-500 text-white font-medium rounded-xl hover:bg-amber-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Update Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app>
