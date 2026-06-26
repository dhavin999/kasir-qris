<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan - {{ $order->order_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        }
        /* Progress line animation */
        .progress-line { transition: width 0.5s ease-in-out; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col items-center py-8 px-4">

    <div class="w-full max-w-md w-full flex-1 flex flex-col">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="inline-block bg-blue-100 text-blue-700 font-mono font-bold text-sm px-4 py-1.5 rounded-full mb-3 shadow-sm border border-blue-200">
                #{{ $order->order_code }}
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Halo, {{ $order->customer_name }}!</h2>
            <p class="text-sm text-slate-500 mt-1 font-medium">Terima kasih telah memesan. Pantau pesanan Anda di sini.</p>
        </div>

        <!-- Status Card Dynamic Container -->
        <div id="status-container" class="glass-panel rounded-3xl p-6 mb-6 relative overflow-hidden">
            <!-- Background Decoration -->
            <div id="status-bg-glow" class="absolute -top-10 -right-10 w-32 h-32 rounded-full blur-3xl opacity-30 transition-colors duration-500"></div>
            
            <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-4">Status Saat Ini</p>
            
            <div class="flex items-center space-x-5">
                <div id="status-icon-wrapper" class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl shadow-inner transition-colors duration-500">
                    <i id="status-icon" class="fa-solid"></i>
                </div>
                <div class="flex-1">
                    <h3 id="status-title" class="text-lg font-black text-slate-800 mb-1 transition-colors duration-500"></h3>
                    <p id="status-desc" class="text-xs text-slate-500 font-medium leading-relaxed"></p>
                </div>
            </div>

            <!-- Payment Alert for Menunggu Status -->
            <div id="payment-alert" class="hidden mt-5 bg-orange-50 border border-orange-200 rounded-xl p-4 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-triangle-exclamation text-orange-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-orange-800 uppercase tracking-wider">Perhatian!</h3>
                        <div class="mt-1 text-xs text-orange-700 font-medium leading-relaxed">
                            Silakan segera lakukan pembayaran di meja kasir. <br>
                            <span class="font-bold text-orange-800">Pesanan Anda tidak akan diproses oleh koki jika belum dibayar.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Progress Bar -->
            <div class="mt-6 pt-6 border-t border-slate-100 hidden" id="progress-container">
                <div class="flex justify-between text-[10px] font-bold text-slate-400 uppercase mb-2 px-1">
                    <span>Bayar</span>
                    <span>Masak</span>
                    <span>Saji</span>
                </div>
                <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                    <div id="progress-bar" class="h-full bg-blue-500 rounded-full progress-line" style="width: 0%;"></div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 mb-6 flex-1">
            <h4 class="font-bold text-sm text-slate-800 uppercase tracking-wider mb-4 flex items-center">
                <i class="fa-solid fa-receipt mr-2 text-blue-500"></i> Rincian Pesanan
            </h4>
            <div class="space-y-3 mb-6">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-bold text-slate-800 text-sm">
                                <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md mr-1">{{ $item->quantity }}x</span> 
                                {{ $item->menu->name }}
                            </p>
                            @if($item->notes)
                                <p class="text-xs italic text-amber-600 mt-1.5 bg-amber-50 px-2.5 py-1 rounded-lg inline-block border border-amber-100">
                                    <i class="fa-solid fa-caret-right mr-1"></i> {{ $item->notes }}
                                </p>
                            @endif
                        </div>
                        <span class="font-bold text-slate-600 text-sm whitespace-nowrap ml-4">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-slate-100 pt-4 space-y-2 text-sm">
                <div class="flex justify-between text-slate-500 font-medium">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-emerald-600 font-medium">
                        <span>Diskon ({{ $order->promo->code ?? 'Promo' }})</span>
                        <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-base font-black text-slate-800 pt-2 border-t border-slate-100 mt-2">
                    <span>Total Pembayaran</span>
                    <span class="text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-3 mt-auto">
            <a href="{{ route('customer.order') }}" class="flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl text-sm transition shadow-lg shadow-blue-600/30">
                <i class="fa-solid fa-utensils mr-2"></i> Pesan Menu Lainnya
            </a>
            <a href="{{ route('customer.history') }}" class="flex items-center justify-center w-full bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold py-4 rounded-2xl text-sm transition shadow-sm">
                <i class="fa-solid fa-clock-rotate-left mr-2 text-blue-500"></i> Lihat Riwayat Pesanan Saya
            </a>
            <div class="text-center mt-4">
                <span class="text-[10px] text-slate-400 font-medium flex items-center justify-center">
                    <i class="fa-solid fa-circle text-[6px] text-emerald-500 mr-1.5 animate-pulse"></i> Halaman diperbarui secara otomatis
                </span>
            </div>
        </div>
    </div>

    <script>
        // Data status dari server
        let currentStatus = "{{ $order->status }}";
        const orderCode = "{{ $order->order_code }}";
        const checkUrl = "{{ route('customer.status', $order->order_code) }}";

        // Konfigurasi UI untuk setiap status
        const statusConfig = {
            'Menunggu': {
                icon: 'fa-wallet',
                colorClass: 'blue',
                title: 'Menunggu Konfirmasi',
                desc: 'Harap melakukan pembayaran di kasir, untuk menkonfirmasi pesanan anda.',
                progress: 10
            },
            'Diproses': {
                icon: 'fa-fire-burner',
                colorClass: 'blue',
                title: 'Sedang Dimasak',
                desc: 'Pesanan Anda sedang disiapkan oleh koki terbaik kami di dapur.',
                progress: 50
            },
            'Siap Disajikan': {
                icon: 'fa-bell-concierge',
                colorClass: 'purple',
                title: 'Siap Diantar',
                desc: 'Pesanan Anda sudah selesai dimasak dan akan segera diantarkan ke meja Anda.',
                progress: 90
            },
            'Selesai': {
                icon: 'fa-check-double',
                colorClass: 'emerald',
                title: 'Selesai',
                desc: 'Pesanan telah disajikan. Selamat menikmati hidangan Anda!',
                progress: 100
            },
            'Dibatalkan': {
                icon: 'fa-ban',
                colorClass: 'red',
                title: 'Pesanan Dibatalkan',
                desc: 'Mohon maaf, pesanan Anda telah dibatalkan.',
                progress: 0
            }
        };

        function updateUI(status) {
            const config = statusConfig[status] || statusConfig['Menunggu'];
            
            const iconWrapper = document.getElementById('status-icon-wrapper');
            const icon = document.getElementById('status-icon');
            const title = document.getElementById('status-title');
            const desc = document.getElementById('status-desc');
            const bgGlow = document.getElementById('status-bg-glow');
            const progressContainer = document.getElementById('progress-container');
            const progressBar = document.getElementById('progress-bar');

            // Reset classes
            iconWrapper.className = `w-16 h-16 rounded-2xl flex items-center justify-center text-3xl shadow-inner transition-colors duration-500 bg-${config.colorClass}-100 text-${config.colorClass}-600`;
            icon.className = `fa-solid ${config.icon}`;
            title.className = `text-lg font-black transition-colors duration-500 text-${config.colorClass}-700`;
            bgGlow.className = `absolute -top-10 -right-10 w-32 h-32 rounded-full blur-3xl opacity-40 transition-colors duration-500 bg-${config.colorClass}-400`;
            
            // Update Text
            title.innerText = config.title;
            desc.innerText = config.desc;

            // Toggle Payment Alert
            const paymentAlert = document.getElementById('payment-alert');
            if (status === 'Menunggu') {
                paymentAlert.classList.remove('hidden');
            } else {
                paymentAlert.classList.add('hidden');
            }

            // Update Progress Bar
            if (status !== 'Dibatalkan') {
                progressContainer.classList.remove('hidden');
                progressBar.style.width = config.progress + '%';
                progressBar.className = `h-full rounded-full progress-line bg-${config.colorClass}-500`;
            } else {
                progressContainer.classList.add('hidden');
            }
        }

        // Initialize UI
        updateUI(currentStatus);

        // Polling setiap 3 detik
        setInterval(async () => {
            try {
                const response = await fetch(checkUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    const data = await response.json();
                    if (data.status && data.status !== currentStatus) {
                        currentStatus = data.status;
                        updateUI(currentStatus);
                    }
                }
            } catch (error) {
                console.error("Gagal mengecek status pesanan", error);
            }
        }, 3000);
    </script>
</body>
</html>
