<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Kerja Kasir - terralog</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #94a3b8; }
        
        /* Modern Card Styling */
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        
        .order-card {
            transition: all 0.2s ease-in-out;
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">
    <!-- Overlay untuk Sidebar di Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <div id="cashierSidebar" class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.1)] z-50 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out border-r border-blue-500/30">
            <div class="p-6 border-b border-blue-500/30 flex justify-between items-center relative overflow-hidden">
                <!-- Abstract subtle shape in header -->
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                
                <div class="flex items-center space-x-3 relative z-10">
                    <div class="w-10 h-10 bg-white rounded-xl shadow-lg shadow-black/5 ring-1 ring-white/50 overflow-hidden flex items-center justify-center p-1">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <h1 class="font-extrabold text-xl text-white tracking-tight drop-shadow-sm">KASIR</h1>
                </div>
                <button onclick="toggleSidebar()" class="md:hidden text-blue-200 hover:text-white relative z-10 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto custom-scrollbar">
                <p class="px-4 text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-3 mt-2">Daftar Pesanan</p>
                
                <a href="{{ route('kasir.orders.incoming') }}" class="block px-4 py-3 rounded-xl transition-all duration-300 relative group overflow-hidden {{ Request::routeIs('kasir.orders.incoming') ? 'bg-white text-blue-700 shadow-lg shadow-black/5 font-bold ring-1 ring-black/5' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    @if(Request::routeIs('kasir.orders.incoming')) <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500 rounded-r-md"></div> @endif
                    <span class="mr-3 w-5 inline-block text-center transition-transform group-hover:scale-110"><i class="fa-solid fa-file-invoice-dollar"></i></span> Belum Dibayar
                </a>
                
                <a href="{{ route('kasir.orders.processing') }}" class="block px-4 py-3 rounded-xl transition-all duration-300 relative group overflow-hidden {{ Request::routeIs('kasir.orders.processing') ? 'bg-white text-blue-700 shadow-lg shadow-black/5 font-bold ring-1 ring-black/5' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    @if(Request::routeIs('kasir.orders.processing')) <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500 rounded-r-md"></div> @endif
                    <span class="mr-3 w-5 inline-block text-center transition-transform group-hover:scale-110"><i class="fa-solid fa-fire-burner"></i></span> Sedang Dimasak
                </a>
                
                <a href="{{ route('kasir.orders.ready') }}" class="block px-4 py-3 rounded-xl transition-all duration-300 relative group overflow-hidden {{ Request::routeIs('kasir.orders.ready') ? 'bg-white text-blue-700 shadow-lg shadow-black/5 font-bold ring-1 ring-black/5' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    @if(Request::routeIs('kasir.orders.ready')) <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500 rounded-r-md"></div> @endif
                    <span class="mr-3 w-5 inline-block text-center transition-transform group-hover:scale-110"><i class="fa-solid fa-bell-concierge"></i></span> Siap Diantar
                </a>

                <div class="h-px w-full bg-blue-500/30 my-4"></div>

                <p class="px-4 text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-3 mt-4">Manajemen</p>
                
                <a href="{{ route('kasir.tables.index') }}" class="block px-4 py-3 rounded-xl transition-all duration-300 relative group overflow-hidden {{ Request::routeIs('kasir.tables.index') ? 'bg-white text-blue-700 shadow-lg shadow-black/5 font-bold ring-1 ring-black/5' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    @if(Request::routeIs('kasir.tables.index')) <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500 rounded-r-md"></div> @endif
                    <span class="mr-3 w-5 inline-block text-center transition-transform group-hover:scale-110"><i class="fa-solid fa-chair"></i></span> Kelola Status Meja
                </a>


            </nav>
        </div>

        <div class="flex-grow flex flex-col overflow-hidden">
            <!-- Navbar -->
            <nav class="bg-white px-6 py-4 border-b border-slate-200 flex justify-between items-center sticky top-0 z-10 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                <div class="flex items-center">
                    <button onclick="toggleSidebar()" class="md:hidden mr-4 text-slate-600 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h1 class="font-extrabold text-xl text-blue-700 tracking-tight hidden sm:block">DASHBOARD</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('kasir.order.create') }}" class="hidden sm:flex text-sm bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all items-center shadow-md shadow-blue-500/20 hover:shadow-blue-500/40 hover:-translate-y-0.5">
                        <i class="fa-solid fa-plus mr-2"></i> Buat Pesanan Baru
                    </a>
                    
                    <div class="h-8 w-px bg-slate-200 mx-2 hidden md:block"></div>
                    
                    <a href="{{ route('kasir.history') }}" class="hidden sm:flex text-sm bg-white hover:bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-xl font-bold transition-all items-center {{ Request::routeIs('kasir.history') ? 'text-blue-600 ring-1 ring-blue-200 shadow-md bg-blue-50/50' : 'text-slate-700 shadow-sm hover:shadow-md hover:text-blue-600' }} group">
                        <i class="fa-solid fa-clock-rotate-left mr-2 {{ Request::routeIs('kasir.history') ? 'text-blue-500' : 'text-slate-400 group-hover:text-blue-500' }} transition-colors"></i> Riwayat
                    </a>

                    <a href="{{ route('kasir.endOfDay') }}" target="_blank" class="hidden sm:flex text-sm bg-white hover:bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-xl font-bold transition-all items-center text-slate-700 shadow-sm hover:shadow-md hover:text-blue-600 group">
                        <i class="fa-solid fa-file-invoice-dollar mr-2 text-slate-400 group-hover:text-blue-500 transition-colors"></i> Rekap Harian
                    </a>

                    <div class="h-8 w-px bg-slate-200 mx-2 hidden sm:block"></div>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah kamu yakin ingin keluar dari sistem kasir?')" class="text-sm bg-red-50 hover:bg-red-500 hover:text-white text-red-600 px-4 py-2.5 rounded-xl font-bold transition-all flex items-center shadow-sm border border-red-100 hover:border-red-500 hover:shadow-md hover:shadow-red-500/20 group">
                            <i class="fa-solid fa-power-off mr-2 hidden sm:inline-block transition-colors"></i> Keluar
                        </button>
                    </form>
                </div>
            </nav>

            <main class="p-6 flex-1 overflow-y-auto custom-scrollbar bg-slate-50/50 relative">
                @if(session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-check-circle text-emerald-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-emerald-700 font-medium">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-medium">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modal Batal (Global for Cashier) -->
    <div id="cancelModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex-col justify-center items-center transition-opacity opacity-0">
        <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl transform scale-95 transition-transform" id="cancelModalContent">
            <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Batalkan Pesanan?</h3>
            <p class="text-sm text-slate-500 text-center mb-6">Anda yakin ingin membatalkan pesanan <span id="cancelOrderCode" class="font-bold text-slate-800"></span>? Aksi ini tidak dapat dikembalikan.</p>
            
            <form id="cancelForm" method="POST" class="flex space-x-3">
                @csrf
                <input type="hidden" name="status" value="Dibatalkan">
                <button type="button" onclick="closeCancelModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-colors">Tutup</button>
                <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl transition-colors shadow-md shadow-red-500/20">Ya, Batalkan</button>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('cashierSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function openCancelModal(actionUrl, orderCode) {
            const modal = document.getElementById('cancelModal');
            const modalContent = document.getElementById('cancelModalContent');
            
            document.getElementById('cancelForm').action = actionUrl;
            document.getElementById('cancelOrderCode').innerText = orderCode;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancelModal');
            const modalContent = document.getElementById('cancelModalContent');
            
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Auto Refresh logic for specific pages could be placed here or in specific views
    </script>
    @yield('scripts')
</body>
</html>

