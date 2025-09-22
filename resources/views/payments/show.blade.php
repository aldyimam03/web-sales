<x-app>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Detail Pembayaran</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 fade-in space-y-6">
            <p><strong>Kode Pembayaran:</strong> {{ $payment->kode_pembayaran }}</p>
            <p><strong>Tanggal Bayar:</strong> {{ $payment->tanggal_bayar->format('d M Y') }}</p>
            <p><strong>Jumlah:</strong> Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
            <p><strong>Penjualan:</strong> {{ $payment->sale->kode_penjualan ?? '-' }}</p>
            <p><strong>Catatan:</strong> {{ $payment->catatan ?? '-' }}</p>

            <div class="flex justify-end pt-4">
                <a href="{{ route('payments.index') }}" class="px-6 py-3 bg-slate-100 rounded-xl">Kembali</a>
            </div>
        </div>
    </div>
</x-app>
