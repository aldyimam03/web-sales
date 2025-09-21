<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel App') }}</title>
    @vite('resources/css/app.css')
    <style>
        /* Custom animations */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Smooth sidebar transition */
        .sidebar-transition {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">

    <!-- Mobile overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed left-0 top-0 h-full w-64 bg-white shadow-xl z-50 sidebar-transition transform lg:transform-none flex flex-col">

        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-slate-200">
            <h1 class="text-xl font-bold text-slate-800">My App</h1>
            <button id="close-sidebar" class="lg:hidden text-slate-500 hover:text-slate-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Menu + Logout -->
        <nav class="p-4 custom-scrollbar overflow-y-auto flex-1 flex flex-col justify-between">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A7 7 0 0112 15a7 7 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('sales.index') }}"
                        class="flex items-center px-4 py-3 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors {{ request()->routeIs('sales.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A7 7 0 0112 15a7 7 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                        Sale
                    </a>
                </li>
                <li>
                    <a href="{{ route('items.index') }}"
                        class="flex items-center px-4 py-3 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors {{ request()->routeIs('items.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14-7a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2h14zM5 11h14">
                            </path>
                        </svg>
                        Item
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}"
                        class="flex items-center px-4 py-3 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A7 7 0 0112 15a7 7 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                        Users
                    </a>
                </li>
            </ul>

            <!-- Logout -->
            <div class="mt-6 border-t border-slate-200 pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-3 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-9V7"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Main content -->
    <main class="lg:ml-64 min-h-screen">
        <!-- Top bar -->
        <header class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-6 py-4">
                <button id="toggle-sidebar"
                    class="lg:hidden p-2 rounded-md text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="hidden lg:block">
                    <h2 class="text-lg font-semibold text-slate-800">
                        @if (request()->routeIs('items.index'))
                            Dashboard Items
                        @elseif(request()->routeIs('items.create'))
                            Tambah Item Baru
                        @elseif(request()->routeIs('items.edit'))
                            Edit Item
                        @elseif(request()->routeIs('items.show'))
                            Detail Item
                        @else
                            Dashboard
                        @endif
                    </h2>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-sm text-slate-500">
                        {{ now()->format('d M Y') }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="p-6 fade-in">
            {{ $slot }}
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-sidebar');
            const closeBtn = document.getElementById('close-sidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            toggleBtn.addEventListener('click', openSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeSidebar();
                }
            });

            // Initialize sidebar state on mobile
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>

</html>
