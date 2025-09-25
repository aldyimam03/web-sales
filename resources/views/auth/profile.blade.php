<x-app>
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Profil Saya</h1>
            <p class="text-slate-600">Perbarui informasi akun Anda</p>
        </div>

        <!-- Flash -->
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Info User -->
            <div class="flex items-center space-x-6 mb-8">
                <img class="w-20 h-20 rounded-full object-cover aspect-square"
                    src="{{ Auth::user()->profile_photo_url ? asset(Auth::user()->profile_photo_url) : asset('images/default-avatar.jpg') }}"
                    alt="{{ Auth::user()->name }}">
                <div>
                    <p class="text-lg font-medium text-slate-800">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-slate-600">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 ring-2 ring-red-200 @enderror"
                        placeholder="Masukkan nama Anda">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 ring-2 ring-red-200 @enderror"
                        placeholder="Masukkan email Anda">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Profil -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Profil</label>
                    <input type="file" name="profile_photo"
                        class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4
                               file:rounded-md file:border-0 file:text-sm file:font-semibold
                               file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('profile_photo')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru (opsional)</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 ring-2 ring-red-200 @enderror"
                        placeholder="Masukkan password baru">
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ulangi password baru">
                </div>

                <!-- Tombol -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('dashboard') }}"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">
                        Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-amber-500 text-white font-medium rounded-xl hover:bg-amber-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app>
