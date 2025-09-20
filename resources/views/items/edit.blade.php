<x-app>
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Edit Item</h1>
            <p class="text-slate-600">Perbarui informasi item "{{ $item->nama }}"</p>
        </div>

        <!-- Flash Messages -->
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg fade-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

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

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 fade-in">
            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Kode Field -->
                <div class="form-group">
                    <label for="kode" class="block text-sm font-semibold text-slate-700 mb-2">
                        Kode Item <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode" id="kode" value="{{ old('kode', $item->kode) }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('kode') border-red-500 ring-2 ring-red-200 @enderror"
                        placeholder="Masukkan kode item">
                    @error('kode')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Nama Field -->
                <div class="form-group">
                    <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">
                        Nama Item <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $item->nama) }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('nama') border-red-500 ring-2 ring-red-200 @enderror"
                        placeholder="Masukkan nama item">
                    @error('nama')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Harga Field -->
                <div class="form-group">
                    <label for="harga" class="block text-sm font-semibold text-slate-700 mb-2">
                        Harga <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-slate-500 font-medium">Rp</span>
                        </div>
                        <input type="number" name="harga" id="harga" value="{{ old('harga', $item->harga) }}"
                            class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('harga') border-red-500 ring-2 ring-red-200 @enderror"
                            placeholder="0" min="0">
                    </div>
                    @error('harga')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Gambar Field -->
                <div class="form-group">
                    <label for="gambar" class="block text-sm font-semibold text-slate-700 mb-2">
                        Gambar Item
                    </label>

                    <!-- Current Image -->
                    @if ($item->gambar)
                        <div class="mb-4 bg-slate-50 rounded-xl p-4" id="current-image-container">
                            <p class="text-sm font-medium text-slate-700 mb-3">Gambar saat ini:</p>
                            <div class="flex items-start space-x-4">
                                <img id="current-image" src="{{ asset('storage/' . $item->gambar) }}"
                                    alt="{{ $item->nama }}"
                                    class="h-24 w-24 object-cover rounded-lg border-2 border-slate-200 transition-all duration-300">
                                <div class="flex-1">
                                    <p class="text-sm text-slate-600 font-medium">{{ basename($item->gambar) }}</p>
                                    <p class="text-xs text-slate-500 mt-1">Pilih gambar baru di bawah untuk mengganti
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Upload Area -->
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-blue-400 hover:bg-blue-50/50 transition-all duration-200 cursor-pointer"
                        id="upload-area">
                        <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        <p class="text-slate-600 mb-2 font-medium">
                            @if ($item->gambar)
                                Klik untuk mengganti gambar atau drag & drop
                            @else
                                Klik untuk upload gambar atau drag & drop
                            @endif
                        </p>
                        <p class="text-sm text-slate-500">PNG, JPG, JPEG maksimal 2MB</p>

                        <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden">
                    </div>

                    <!-- Preview Container untuk gambar baru -->
                    <div id="preview-container" class="hidden mt-4">
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex items-start space-x-4">
                                <img id="preview-image"
                                    class="h-24 w-24 object-cover rounded-lg border-2 border-amber-300">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-amber-800 mb-1">Preview gambar baru:</p>
                                    <p id="file-info" class="text-xs text-amber-700 mb-3"></p>
                                    <button type="button" id="cancel-preview"
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Batalkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (!$item->gambar)
                        <div id="no-image-text" class="mt-3 text-sm text-slate-500 italic text-center">
                            <svg class="w-5 h-5 mx-auto mb-1 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Belum ada gambar
                        </div>
                    @endif

                    @error('gambar')
                        <p class="mt-3 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-slate-200">
                    <a href="{{ route('items.index') }}"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-amber-500 text-white font-medium rounded-xl hover:bg-amber-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('gambar');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');
            const fileInfo = document.getElementById('file-info');
            const cancelBtn = document.getElementById('cancel-preview');
            const currentImage = document.getElementById('current-image');
            const noImageText = document.getElementById('no-image-text');

            // Click to upload
            uploadArea.addEventListener('click', () => fileInput.click());

            // Drag and drop functionality
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('border-blue-500', 'bg-blue-100');
            });

            uploadArea.addEventListener('dragleave', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-500', 'bg-blue-100');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-500', 'bg-blue-100');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        fileInput.files = files;
                        handleFileSelect(file);
                    } else {
                        alert('File yang di-drop bukan gambar!');
                    }
                }
            });

            // File input change
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    handleFileSelect(file);
                } else {
                    resetPreview();
                }
            });

            // Cancel preview
            cancelBtn.addEventListener('click', function() {
                fileInput.value = '';
                resetPreview();
            });

            function handleFileSelect(file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('File yang dipilih bukan gambar!');
                    fileInput.value = '';
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2048 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB');
                    fileInput.value = '';
                    return;
                }

                // Show preview
                const url = URL.createObjectURL(file);
                previewImage.src = url;
                fileInfo.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                previewContainer.classList.remove('hidden');

                // Dim current image
                if (currentImage) {
                    currentImage.style.opacity = '0.4';
                    currentImage.style.filter = 'grayscale(50%)';
                }
                if (noImageText) {
                    noImageText.classList.add('hidden');
                }
            }

            function resetPreview() {
                previewContainer.classList.add('hidden');

                // Restore current image
                if (currentImage) {
                    currentImage.style.opacity = '1';
                    currentImage.style.filter = 'none';
                }
                if (noImageText) {
                    noImageText.classList.remove('hidden');
                }

                // Clean up URL
                if (previewImage.src && previewImage.src.startsWith('blob:')) {
                    URL.revokeObjectURL(previewImage.src);
                }
            }

            // Format number input with thousand separators (optional enhancement)
            const hargaInput = document.getElementById('harga');
            hargaInput.addEventListener('blur', function(e) {
                const value = e.target.value;
                if (value) {
                    // You can add formatting here if needed
                    console.log('Harga:', new Intl.NumberFormat('id-ID').format(value));
                }
            });
        });
    </script>

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

        .form-group {
            transition: all 0.2s ease;
        }

        input:focus {
            transform: scale(1.02);
        }

        #upload-area:hover {
            transform: translateY(-2px);
        }

        /* Custom file input styling */
        input[type="file"]::-webkit-file-upload-button {
            visibility: hidden;
        }

        /* Loading animation for better UX */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }
    </style>
</x-app>
