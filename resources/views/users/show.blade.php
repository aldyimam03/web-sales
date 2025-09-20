<x-app>
    <div class="max-w-3xl mx-auto py-6">
        <h1 class="text-2xl font-bold mb-6">Detail User</h1>

        <div class="bg-white shadow rounded-2xl p-6 space-y-3">
            <p><strong>Nama:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Dibuat:</strong> {{ $user->created_at->format('d M Y H:i') }}</p>
            <p><strong>Terakhir diperbarui:</strong> {{ $user->updated_at->format('d M Y H:i') }}</p>
        </div>

        <div class="mt-6">
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Kembali
            </a>
        </div>
    </div>
</x-app>
