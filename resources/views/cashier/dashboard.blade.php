<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Kerja Kasir - terralog</title>
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
        
        /* Pulse Animation for New Orders */
        @keyframes pulse-ring {
            0% { transform: scale(0.8); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.8); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .indicator-pulse {
            animation: pulse-ring 2s infinite;
        }
    </style>
</head>
<body class="bg-slate-50/50 text-slate-800 h-screen flex flex-col overflow-hidden">

    <!-- Navbar -->
    <nav class="bg-white px-6 py-4 border-b border-slate-200 flex justify-between items-center sticky top-0 z-50 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
        <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-white rounded-xl shadow-lg shadow-black/5 ring-1 ring-blue-100 overflow-hidden flex items-center justify-center p-1">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="font-extrabold text-xl tracking-tight text-blue-700">KASIR DASHBOARD</h1>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('kasir.order.create') }}" class="hidden sm:flex text-sm bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all items-center shadow-md shadow-blue-500/20 hover:shadow-blue-500/40 hover:-translate-y-0.5">
                <i class="fa-solid fa-plus mr-2"></i> Buat Pesanan Baru
            </a>
            
            <div class="h-8 w-px bg-slate-200 mx-2 hidden md:block"></div>
            
            <a href="{{ route('kasir.history') }}" class="hidden sm:flex text-sm bg-white hover:bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-xl font-bold transition-all items-center text-slate-700 shadow-sm hover:shadow-md hover:text-blue-600 group">
                <i class="fa-solid fa-clock-rotate-left mr-2 text-slate-400 group-hover:text-blue-500 transition-colors"></i> Riwayat
            </a>
            <a href="{{ route('kasir.endOfDay') }}" target="_blank" class="hidden sm:flex text-sm bg-white hover:bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-xl font-bold transition-all items-center text-slate-700 shadow-sm hover:shadow-md hover:text-blue-600 group">
                <i class="fa-solid fa-file-invoice-dollar mr-2 text-slate-400 group-hover:text-blue-500 transition-colors"></i> Rekap Harian
            </a>

            <div class="h-8 w-px bg-slate-200 mx-2 hidden sm:block"></div>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Apakah kamu yakin ingin keluar dari sistem kasir?')" class="text-sm bg-red-50 hover:bg-red-500 hover:text-white text-red-600 px-4 py-2.5 rounded-xl font-bold transition-all flex items-center shadow-sm border border-red-100 hover:border-red-500 hover:shadow-md hover:shadow-red-500/20 group">
                    <i class="fa-solid fa-power-off mr-2 transition-colors"></i> Keluar
                </button>
            </form>
            
        </div>
    </nav>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mx-6 mt-4 rounded-r-lg shadow-sm">
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

    <!-- Kanban Board -->
    <div class="flex-1 p-6 grid grid-cols-1 lg:grid-cols-4 gap-6 overflow-hidden">
        
        <!-- Column 0: Input Meja & Antrian -->
        <div class="bg-slate-100/50 rounded-2xl border border-slate-200/60 flex flex-col h-full overflow-hidden relative">
            <div class="bg-white px-5 py-4 border-b border-slate-200 shadow-sm z-10">
                <h2 class="font-bold text-slate-800 text-sm uppercase tracking-wide flex items-center">
                    <i class="fa-solid fa-chair mr-2 text-indigo-600"></i> Meja Ditempati
                </h2>
                <p class="text-[10px] text-slate-500 font-medium mt-0.5">Input order selesai diantar</p>
                <form action="{{ route('kasir.table.setOccupied') }}" method="POST" class="mt-3 flex gap-2">
                    @csrf
                    <input type="text" name="order_code" placeholder="Kode Pesanan" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm outline-none focus:border-indigo-500" />
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-sm font-bold shadow-sm">
                        <i class="fa-solid fa-lock"></i>
                    </button>
                </form>
            </div>
            
            <div class="p-4 space-y-3 overflow-y-auto flex-1 custom-scrollbar">
                @forelse($occupiedTables as $table)
                    <div class="glass-panel rounded-xl p-3 border-l-4 border-l-indigo-500 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-slate-800">Meja {{ $table->table_number }}</p>
                            @if($table->is_unlock_requested)
                                <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">Request Buka!</span>
                            @else
                                <span class="text-[10px] text-slate-500">Terisi</span>
                            @endif
                        </div>
                        <i class="fa-solid fa-lock text-slate-300"></i>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-slate-400 opacity-60">
                        <i class="fa-solid fa-chair text-3xl mb-2"></i>
                        <p class="text-xs font-medium">Belum ada meja ditempati</p>
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Column 1: Pesanan Masuk (Needs Payment) -->
        <div class="bg-slate-100/50 rounded-2xl border border-slate-200/60 flex flex-col h-full overflow-hidden relative">
            <div class="bg-white px-5 py-4 border-b border-slate-200 flex justify-between items-center shadow-sm z-10">
                <div class="flex items-center space-x-3">
                    <div class="bg-amber-100 text-amber-600 p-2 rounded-lg">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-sm uppercase tracking-wide">1. Belum Dibayar</h2>
                        <p class="text-[10px] text-slate-500 font-medium mt-0.5">Konfirmasi pembayaran disini</p>
                    </div>
                </div>
                <span class="bg-amber-100 text-amber-700 font-bold text-sm px-3 py-1 rounded-full border border-amber-200 shadow-sm" id="count-menunggu">{{ $incomingOrders->count() }}</span>
            </div>
            
            <div class="p-4 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
                @forelse($incomingOrders as $order)
                    <div class="glass-panel rounded-xl p-4 order-card border-l-4 border-l-amber-500 relative" data-id="{{ $order->id }}">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="bg-slate-100 text-slate-600 text-xs font-mono font-bold px-2 py-0.5 rounded-md border border-slate-200">{{ $order->order_code }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium bg-slate-50 px-2 py-0.5 rounded-full"><i class="fa-regular fa-clock mr-1"></i>{{ $order->created_at->format('H:i') }}</span>
                                </div>
                                <h3 class="font-bold text-slate-800 text-base">
                                    @if($order->order_type === 'Take Away')
                                        <span class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md text-sm"><i class="fa-solid fa-bag-shopping mr-1"></i> Take Away</span>
                                    @else
                                        <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md text-sm mr-1"><i class="fa-solid fa-utensils mr-1"></i> Dine-In</span>
                                        Meja {{ $order->table->table_number ?? '-' }}
                                    @endif
                                </h3>
                                <p class="text-xs font-medium text-slate-500 mt-1"><i class="fa-regular fa-user mr-1"></i> {{ $order->customer_name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-slate-500 font-medium mb-0.5">Total Tagihan</p>
                                <span class="text-sm font-black text-slate-800 bg-emerald-50 text-emerald-700 px-2 py-1 rounded-lg border border-emerald-100 block whitespace-nowrap">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 rounded-lg p-3 text-xs text-slate-600 space-y-2 mb-4 border border-slate-100">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-start">
                                    <span class="font-medium text-slate-700"><span class="text-indigo-600 font-bold mr-1">{{ $item->quantity }}x</span> {{ $item->menu->name }}</span>
                                    <span class="text-slate-400 font-mono whitespace-nowrap ml-2">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                                @if($item->notes) 
                                    <p class="text-[11px] text-amber-600 italic bg-amber-50 px-2 py-1 rounded-md mt-1 border border-amber-100 border-dashed">
                                        <i class="fa-solid fa-caret-right mr-1"></i> {{ $item->notes }}
                                    </p> 
                                @endif
                            @endforeach
                        </div>

                        <div class="flex flex-col space-y-2">
                            <form action="{{ route('kasir.updateStatus', $order->id) }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="status" value="Diproses">
                                <button type="submit" onclick="return confirm('Konfirmasi bahwa pembayaran sebesar Rp {{ number_format($order->total_price, 0, ',', '.') }} telah diterima? Pesanan akan diteruskan ke dapur.')" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm py-2.5 rounded-xl transition shadow-md shadow-emerald-500/20 flex justify-center items-center">
                                    <i class="fa-solid fa-check-double mr-2"></i> Terima Pembayaran dan Masak
                                </button>
                            </form>
                            <div class="flex space-x-2">
                                <a href="{{ route('kasir.printReceipt', $order->id) }}" target="_blank" class="flex-1 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 text-center py-2 rounded-xl text-xs font-bold transition shadow-sm">
                                    <i class="fa-solid fa-receipt mr-1"></i> Struk
                                </a>
                                <button type="button" onclick="openCancelModal('{{ route('kasir.updateStatus', $order->id) }}', '{{ $order->order_code }}')" class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs py-2 rounded-xl border border-red-200 transition shadow-sm">
                                        <i class="fa-solid fa-xmark mr-1"></i> Batal
                                    </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-slate-400 opacity-60">
                        <i class="fa-solid fa-mug-hot text-4xl mb-3"></i>
                        <p class="text-sm font-medium">Belum ada pesanan masuk...</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Column 2: Sedang Dimasak -->
        <div class="bg-slate-100/50 rounded-2xl border border-slate-200/60 flex flex-col h-full overflow-hidden relative">
            <div class="bg-white px-5 py-4 border-b border-slate-200 flex justify-between items-center shadow-sm z-10">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                        <i class="fa-solid fa-fire-burner"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-sm uppercase tracking-wide">2. Sedang Dimasak</h2>
                        <p class="text-[10px] text-slate-500 font-medium mt-0.5">Dapur sedang memproses</p>
                    </div>
                </div>
                <span class="bg-blue-100 text-blue-700 font-bold text-sm px-3 py-1 rounded-full border border-blue-200 shadow-sm">{{ $processingOrders->count() }}</span>
            </div>

            <div class="p-4 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
                @forelse($processingOrders as $order)
                    <div class="glass-panel rounded-xl p-4 order-card border-l-4 border-l-blue-500">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="bg-slate-100 text-slate-600 text-xs font-mono font-bold px-2 py-0.5 rounded-md border border-slate-200 mb-1 inline-block">{{ $order->order_code }}</span>
                                <h3 class="font-bold text-slate-800 text-base">
                                    @if($order->order_type === 'Take Away')
                                        <span class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md text-sm"><i class="fa-solid fa-bag-shopping mr-1"></i> Take Away</span>
                                    @else
                                        <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md text-sm mr-1"><i class="fa-solid fa-utensils mr-1"></i> Dine-In</span>
                                        Meja {{ $order->table->table_number ?? '-' }}
                                    @endif
                                </h3>
                                <p class="text-xs font-medium text-slate-500 mt-1"><i class="fa-regular fa-user mr-1"></i> {{ $order->customer_name }}</p>
                            </div>
                            <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-1 rounded-md border border-emerald-200"><i class="fa-solid fa-check mr-1"></i>Lunas</span>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-3 text-xs text-slate-600 space-y-1 mb-4 border border-slate-100">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-start">
                                    <span class="font-medium text-slate-700"><span class="text-indigo-600 font-bold mr-1">{{ $item->quantity }}x</span> {{ $item->menu->name }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex space-x-2">
                            <form action="{{ route('kasir.updateStatus', $order->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="Siap Disajikan">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-2.5 rounded-xl transition shadow-md shadow-blue-500/20 flex justify-center items-center">
                                    <i class="fa-solid fa-bell-concierge mr-2"></i> Siap Disajikan
                                </button>
                            </form>
                            <a href="{{ route('kasir.printKitchenReceipt', $order->id) }}" target="_blank" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 px-3 py-2.5 rounded-xl text-sm flex items-center justify-center font-bold transition shadow-sm" title="Cetak Struk Dapur">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-slate-400 opacity-60">
                        <i class="fa-solid fa-utensils text-4xl mb-3"></i>
                        <p class="text-sm font-medium">Dapur sedang bersantai...</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Column 3: Siap Diantar -->
        <div class="bg-slate-100/50 rounded-2xl border border-slate-200/60 flex flex-col h-full overflow-hidden relative">
            <div class="bg-white px-5 py-4 border-b border-slate-200 flex justify-between items-center shadow-sm z-10">
                <div class="flex items-center space-x-3">
                    <div class="bg-purple-100 text-purple-600 p-2 rounded-lg">
                        <i class="fa-solid fa-bell-concierge"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-sm uppercase tracking-wide">3. Siap Diantar</h2>
                        <p class="text-[10px] text-slate-500 font-medium mt-0.5">Pesanan menunggu disajikan</p>
                    </div>
                </div>
                <span class="bg-purple-100 text-purple-700 font-bold text-sm px-3 py-1 rounded-full border border-purple-200 shadow-sm">{{ $readyOrders->count() }}</span>
            </div>

            <div class="p-4 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
                @forelse($readyOrders as $order)
                    <div class="glass-panel rounded-xl p-4 order-card border-l-4 border-l-purple-500">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="bg-slate-100 text-slate-600 text-xs font-mono font-bold px-2 py-0.5 rounded-md border border-slate-200 mb-1 inline-block">{{ $order->order_code }}</span>
                                <h3 class="font-bold text-slate-800 text-base">
                                    @if($order->order_type === 'Take Away')
                                        <span class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md text-sm"><i class="fa-solid fa-bag-shopping mr-1"></i> Take Away</span>
                                    @else
                                        <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md text-sm mr-1"><i class="fa-solid fa-utensils mr-1"></i> Dine-In</span>
                                        Meja {{ $order->table->table_number ?? '-' }}
                                    @endif
                                </h3>
                                <p class="text-xs font-medium text-slate-500 mt-1"><i class="fa-regular fa-user mr-1"></i> {{ $order->customer_name }}</p>
                            </div>
                        </div>

                        <div class="flex space-x-2 mt-4">
                            <form action="{{ route('kasir.updateStatus', $order->id) }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="status" value="Selesai">
                                <button type="submit" onclick="return confirm('Tandai pesanan telah selesai diantar/diambil?')" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm py-2.5 rounded-xl transition shadow-md shadow-purple-500/20 flex justify-center items-center">
                                    <i class="fa-solid fa-clipboard-check mr-2"></i> Pesanan Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-slate-400 opacity-60">
                        <i class="fa-solid fa-check-double text-4xl mb-3"></i>
                        <p class="text-sm font-medium">Semua pesanan sudah diantar...</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Modal Cancel Order -->
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
        // Modal Logic
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
        // 1. Logika Auto-Refresh Halaman (5 Detik di background)
        let timeLeft = 5;
        const timerElement = document.getElementById('refreshTimer');
        
        setInterval(() => {
            timeLeft--;
            if (timerElement) {
                timerElement.innerHTML = `<i class="fa-solid fa-rotate mr-2 ${timeLeft < 5 ? 'fa-spin text-amber-500' : ''}"></i> Auto-sync: ${timeLeft}s`;
            }
            if (timeLeft <= 0) {
                const currentCount = document.getElementById('count-menunggu') ? document.getElementById('count-menunggu').innerText : '0';
                localStorage.setItem('prev_order_count', currentCount);
                window.location.reload();
            }
        }, 1000);

        // 2. Fungsi Penghasil Suara "Beep" Notifikasi via Web Audio API
        function playNotificationSound() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                
                let osc1 = audioCtx.createOscillator();
                let gain1 = audioCtx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, audioCtx.currentTime);
                gain1.gain.setValueAtTime(0.1, audioCtx.currentTime);
                osc1.connect(gain1);
                gain1.connect(audioCtx.destination);
                osc1.start();
                osc1.stop(audioCtx.currentTime + 0.15);

                setTimeout(() => {
                    let osc2 = audioCtx.createOscillator();
                    let gain2 = audioCtx.createGain();
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(880, audioCtx.currentTime);
                    gain2.gain.setValueAtTime(0.1, audioCtx.currentTime);
                    osc2.connect(gain2);
                    gain2.connect(audioCtx.destination);
                    osc2.start();
                    osc2.stop(audioCtx.currentTime + 0.25);
                }, 180);
            } catch (e) {
                console.log("Audio play blocked by browser policy.");
            }
        }

        // 3. Deteksi Pesanan Baru
        window.addEventListener('DOMContentLoaded', () => {
            const prevCount = parseInt(localStorage.getItem('prev_order_count') || '0');
            const counterElement = document.getElementById('count-menunggu');
            const currentCount = parseInt(counterElement ? counterElement.innerText : '0');

            if (currentCount > prevCount) {
                playNotificationSound();
                
                // Highlight the counter if new orders arrived
                if(counterElement) {
                    counterElement.classList.add('animate-bounce');
                    setTimeout(() => counterElement.classList.remove('animate-bounce'), 3000);
                }
            }
        });
    </script>
</body>
</html>
