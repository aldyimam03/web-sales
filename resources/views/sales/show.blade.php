<x-app>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Detail Penjualan</h1>
            <p class="text-slate-600">Kode Transaksi: {{ $sale->kode_penjualan }}</p>
        </div>

        <!-- Detail Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 fade-in space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <p class="text-slate-700">
                    <span class="font-semibold">Tanggal:</span>
                    {{ $sale->tanggal_penjualan->format('d M Y') }}
                </p>
                <p class="text-slate-700">
                    <span class="font-semibold">Status:</span>
                    <span
                        class="px-3 py-1 rounded-lg text-xs font-medium
                    @if ($sale->status == 'Belum Dibayar') bg-red-100 text-red-800
                                        @elseif ($sale->status == 'Sudah Dibayar') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800 @endif
                                        ">
                        {{ $sale->status }}
                    </span>
                </p>
                <p class="text-slate-700 sm:col-span-2">
                    <span class="font-semibold">Total:</span>
                    <span class="text-lg font-bold text-slate-900">
                        Rp{{ number_format($sale->total_harga, 0, ',', '.') }}
                    </span>
                </p>
            </div>

            <!-- Items Table -->
            <div>
                <h2 class="text-lg font-semibold text-slate-800 mb-3">Daftar Item</h2>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 uppercase text-xs">
                                <th class="px-4 py-3 text-center">Item</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3 text-center">Harga</th>
                                <th class="px-4 py-3 text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($sale->items as $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-3">{{ $item->item->nama }}</td>
                                    <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-right">Rp{{ number_format($item->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Button -->
            <div class="mt-6 flex">
                <a href="{{ route('sales.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app>
