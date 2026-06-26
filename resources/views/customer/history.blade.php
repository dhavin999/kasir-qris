<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Meja {{ $table->table_number ?? '-' }}</title>
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
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col items-center py-8 px-4 relative overflow-hidden">

    <!-- Background decoration -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 z-0"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-emerald-400 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 z-0"></div>

    <div class="w-full max-w-md w-full flex-1 flex flex-col relative z-10">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('customer.order') }}" class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-slate-600 shadow-sm border border-slate-100 hover:bg-slate-50 transition relative z-20">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="text-center">
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Riwayat Pesanan</h1>
                <p class="text-xs text-slate-500 font-medium">Meja {{ $table->table_number ?? '-' }}</p>
            </div>
            <div class="w-10 h-10"></div> <!-- Spacer -->
        </div>

        @if($orders->isEmpty())
            <div class="glass-panel rounded-3xl p-10 flex flex-col items-center justify-center text-center mt-10">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-4 text-blue-500 text-4xl">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h2 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Pesanan</h2>
                <p class="text-sm text-slate-500 mb-6">Anda belum membuat pesanan apapun di meja ini.</p>
                <a href="{{ route('customer.order') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition shadow-lg shadow-blue-600/30 relative z-20">
                    Pesan Sekarang
                </a>
            </div>
        @else
            <!-- Summary Stats -->
            @php
                $totalSemua = $orders->where('status', '!=', 'Dibatalkan')->sum('total_price');
            @endphp
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-6 mb-6 text-white shadow-lg shadow-blue-600/30 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 text-9xl transform translate-x-4 -translate-y-4">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-wider mb-1 relative z-10">Total Tagihan Anda</p>
                <h2 class="text-3xl font-black relative z-10">Rp {{ number_format($totalSemua, 0, ',', '.') }}</h2>
                <div class="mt-4 flex items-center space-x-4 text-sm font-medium text-blue-100 relative z-10">
                    <div class="flex items-center"><i class="fa-solid fa-utensils mr-2 opacity-80"></i> {{ $orders->count() }} Pesanan</div>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($orders as $order)
                    @php
                        $statusColors = [
                            'Menunggu' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => 'fa-clock'],
                            'Diproses' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'fa-fire-burner'],
                            'Siap Disajikan' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'icon' => 'fa-bell-concierge'],
                            'Selesai' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'icon' => 'fa-check-double'],
                            'Dibatalkan' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'fa-ban'],
                        ];
                        $color = $statusColors[$order->status] ?? $statusColors['Menunggu'];
                    @endphp

                    <div class="glass-panel rounded-3xl p-5 relative overflow-hidden transition-all hover:border-blue-300">
                        <!-- Clickable overlay to status page -->
                        <a href="{{ route('customer.status', $order->order_code) }}" class="absolute inset-0 z-10"></a>
                        
                        <div class="flex justify-between items-start mb-3 relative z-0">
                            <div>
                                <span class="text-xs font-bold font-mono text-slate-500">{{ $order->order_code }}</span>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $order->created_at->format('d M Y • H:i') }}</div>
                            </div>
                            <span class="{{ $color['bg'] }} {{ $color['text'] }} text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center">
                                <i class="fa-solid {{ $color['icon'] }} mr-1"></i> {{ $order->status }}
                            </span>
                        </div>

                        <div class="border-t border-slate-100 pt-3 mb-3 relative z-0">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-bold text-slate-700"><span class="text-blue-600 mr-1">{{ $item->quantity }}x</span> {{ $item->menu->name }}</span>
                                    <span class="text-sm font-medium text-slate-500 whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-between items-end pt-3 border-t border-slate-100 relative z-0">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pembayaran</span>
                            <span class="text-base font-black text-slate-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8 text-center pb-10">
                <a href="{{ route('customer.order') }}" class="inline-flex items-center text-sm font-bold text-blue-600 hover:text-blue-700 relative z-20">
                    <i class="fa-solid fa-plus-circle mr-2"></i> Tambah Pesanan Baru
                </a>
            </div>
        @endif
    </div>

</body>
</html>
