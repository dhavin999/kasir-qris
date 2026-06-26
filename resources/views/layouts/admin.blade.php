<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Aplikasi Kasir</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <!-- Overlay untuk Sidebar di Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-gray-900/50 z-40 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <div class="flex h-[100dvh] overflow-hidden">
        
        <div id="adminSidebar" class="fixed inset-y-0 left-0 w-64 shrink-0 bg-gradient-to-b from-blue-900 to-blue-800 text-white flex flex-col shadow-xl z-50 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-6 border-b border-blue-800/50 flex justify-between items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Terralog Logo" class="h-12 object-contain bg-white p-1 rounded">
                <button onclick="toggleSidebar()" class="md:hidden text-blue-200 hover:text-white">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto no-scrollbar">
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2 mt-4">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl transition-all duration-200 {{ Request::routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20 font-semibold' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <span class="mr-2"><i class="fa-solid fa-chart-line"></i></span> Dashboard
                </a>
                
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2 mt-6">Master Data</p>
                <a href="{{ route('categories.index') }}" class="block px-4 py-3 rounded-xl transition-all duration-200 {{ Request::routeIs('categories.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20 font-semibold' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <span class="mr-2"><i class="fa-solid fa-folder"></i></span> Kategori Menu
                </a>
                <a href="{{ route('menus.index') }}" class="block px-4 py-3 rounded-xl transition-all duration-200 {{ Request::routeIs('menus.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20 font-semibold' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <span class="mr-2"><i class="fa-solid fa-burger"></i></span> Manajemen Menu
                </a>
                <a href="{{ route('stocks.index') }}" class="block px-4 py-3 rounded-xl transition-all duration-200 {{ Request::routeIs('stocks.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20 font-semibold' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <span class="mr-2"><i class="fa-solid fa-box"></i></span> Stok Menu
                </a>
                <a href="{{ route('tables.index') }}" class="block px-4 py-3 rounded-xl transition-all duration-200 {{ Request::routeIs('tables.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20 font-semibold' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <span class="mr-2"><i class="fa-solid fa-chair"></i></span> Manajemen Meja
                </a>

                
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2 mt-6">Pengaturan</p>
                <a href="{{ route('users.index') }}" class="block px-4 py-3 rounded-xl transition-all duration-200 {{ Request::routeIs('users.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20 font-semibold' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <span class="mr-2"><i class="fa-solid fa-users"></i></span> Kelola User
                </a>
            </nav>
        </div>

        <div class="flex-grow flex flex-col overflow-y-auto">
            <header class="bg-white px-6 md:px-8 py-4 md:py-5 flex justify-between items-center border-b border-gray-100 sticky top-0 z-10 backdrop-blur-md bg-white/90 shadow-sm">
                <div class="flex items-center">
                    <button onclick="toggleSidebar()" class="md:hidden mr-4 text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-lg md:text-xl font-bold text-gray-800 tracking-tight">Halaman Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-xs font-medium text-blue-600">{{ Auth::user()->role->name ?? 'Admin' }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-700 to-blue-500 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-blue-500/30 ring-2 ring-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="h-8 w-px bg-gray-200 mx-2 hidden sm:block"></div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah kamu yakin ingin keluar?')" class="text-sm bg-red-50 hover:bg-red-500 hover:text-white text-red-600 px-4 py-2.5 rounded-xl font-bold transition-all flex items-center shadow-sm border border-red-100 hover:border-red-500 hover:shadow-md hover:shadow-red-500/20 group">
                            <i class="fa-solid fa-power-off mr-2 hidden sm:inline-block transition-colors"></i> Keluar
                        </button>
                    </form>
                </div>
            </header>

            <main class="p-6">
                @yield('content')
            </main>
        </div>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>
