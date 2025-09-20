<x-app>
    <div class="max-w-3xl mx-auto py-6">
        <h1 class="text-2xl font-bold mb-6">Detail Item</h1>

        <div class="bg-white shadow rounded p-4 space-y-3">
            <p><strong>Kode:</strong> {{ $item->kode }}</p>
            <p><strong>Nama:</strong> {{ $item->nama }}</p>
            <p><strong>Harga:</strong> Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
            <p>
                <strong>Gambar:</strong><br>
                @if ($item->gambar)
                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}" class="h-32 mt-2">
                @else
                    <span class="text-gray-500 italic">Tidak ada gambar</span>
                @endif
            </p>
        </div>

        <div class="mt-6">
            <a href="{{ route('items.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Kembali</a>
        </div>
    </div>
</x-app>
