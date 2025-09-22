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
        <h1 class="text-2xl font-bold mb-6">Edit Pembayaran</h1>

        <form action="{{ route('payments.update', $payment->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <!-- Penjualan -->
            <div>
                <label class="block text-sm font-semibold mb-2">Penjualan</label>
                <input type="text" value="{{ $payment->sale->kode_penjualan }}"
                    class="w-full border rounded-lg px-3 py-2 bg-slate-100 font-medium" readonly>
            </div>

            <!-- Total Penjualan -->
            <div>
                <label class="block text-sm font-semibold mb-2">Total Penjualan</label>
                <input type="text" value="Rp {{ number_format($payment->sale->total_harga, 0, ',', '.') }}"
                    class="w-full border rounded-lg px-3 py-2 bg-slate-100 font-medium" readonly>
            </div>

            <!-- Sudah Terbayar -->
            <div>
                <label class="block text-sm font-semibold mb-2">Sudah Terbayar</label>
                @php
                    $sudahTerbayar = $payment->sale->payments()->sum('jumlah');
                @endphp
                <input type="text" value="Rp {{ number_format($sudahTerbayar, 0, ',', '.') }}"
                    class="w-full border rounded-lg px-3 py-2 bg-slate-100 font-medium" readonly>
            </div>

            <!-- Sisa Pembayaran -->
            <div>
                <label class="block text-sm font-semibold mb-2">Sisa Pembayaran</label>
                @php
                    $sisa = $payment->sale->total_harga - $sudahTerbayar;
                @endphp
                <input type="text" value="Rp {{ number_format($sisa, 0, ',', '.') }}"
                    class="w-full border rounded-lg px-3 py-2 bg-slate-100 font-medium" readonly>
            </div>


            <!-- Tanggal Bayar -->
            <div>
                <label class="block text-sm font-semibold mb-2">Tanggal Bayar</label>
                <input type="date" name="tanggal_bayar" value="{{ $payment->tanggal_bayar->toDateString() }}"
                    class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <!-- Jumlah Bayar -->
            <div>
                <label class="block text-sm font-semibold mb-2">Jumlah</label>
                <input type="number" step="0.01" name="jumlah" value="{{ $payment->jumlah }}"
                    class="w-full border rounded-lg px-3 py-2" required>
                <p class="text-xs text-slate-500 mt-1">Maksimal: Rp {{ number_format($sisa, 0, ',', '.') }}</p>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-semibold mb-2">Catatan</label>
                <textarea name="catatan" class="w-full border rounded-lg px-3 py-2">{{ $payment->catatan }}</textarea>
            </div>

            <!-- Tombol -->
            <div class="flex justify-between">
                <a href="{{ route('payments.index') }}" class="px-6 py-3 bg-slate-100 rounded-xl">Kembali</a>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</x-app>
