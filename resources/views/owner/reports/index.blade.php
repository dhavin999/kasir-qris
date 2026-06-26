<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Lengkap - Owner</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

    <nav class="bg-gradient-to-r from-blue-700 via-blue-600 to-sky-500 text-white px-8 py-4 shadow-lg shadow-blue-500/20 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                    <i class="fa-solid fa-file-invoice text-xl"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-xl tracking-wide drop-shadow-md">Owner INSIGHTS</h1>
                    <p class="text-xs text-blue-100 font-medium mt-0.5">Laporan Penjualan Lengkap</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('owner.dashboard') }}" class="bg-white/10 hover:bg-white/20 text-sm font-semibold px-4 py-2.5 rounded-xl backdrop-blur-sm transition-all border border-white/10 flex items-center shadow-sm">
                    <i class="fa-solid fa-chart-pie mr-2"></i> Dashboard
                </a>
                <a href="{{ route('owner.stock') }}" class="bg-white/10 hover:bg-white/20 text-sm font-semibold px-4 py-2.5 rounded-xl backdrop-blur-sm transition-all border border-white/10 flex items-center shadow-sm relative">
                    <i class="fa-solid fa-box-open mr-2"></i> Monitor Stok
                    <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-md animate-bounce">!</span>
                </a>
                <div class="w-px h-6 bg-white/20 mx-2"></div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="bg-red-500/90 hover:bg-red-500 text-sm font-bold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-red-500/30 flex items-center">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6 space-y-8 mt-4">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight flex items-center">
                    Laporan Penjualan <i class="fa-solid fa-arrow-trend-up ml-3 text-blue-500"></i>
                </h2>
                <p class="text-slate-500 mt-2 text-sm font-medium">Lihat ringkasan transaksi, filter berdasarkan tanggal, dan unduh laporan.</p>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('owner.reports.excel', request()->all()) }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-2xl font-bold shadow-lg shadow-emerald-500/30 transition-all flex items-center border border-emerald-400">
                    <i class="fa-solid fa-file-excel mr-2.5 text-lg"></i> Export Excel
                </a>
                <a href="{{ route('owner.reports.pdf', request()->all()) }}" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-3 rounded-2xl font-bold shadow-lg shadow-slate-800/30 transition-all flex items-center border border-slate-700">
                    <i class="fa-solid fa-file-pdf mr-2.5 text-lg"></i> Cetak PDF
                </a>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="bg-white p-6 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100">
            <form action="{{ route('owner.reports.index') }}" method="GET" class="flex flex-col md:flex-row items-end space-y-4 md:space-y-0 md:space-x-6">
                <div class="w-full md:w-2/5">
                    <label class="block text-sm font-extrabold text-slate-700 mb-2 flex items-center">
                        <i class="fa-regular fa-calendar mr-2 text-blue-500"></i> Dari Tanggal
                    </label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-semibold text-slate-700 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition-all">
                </div>
                <div class="w-full md:w-2/5">
                    <label class="block text-sm font-extrabold text-slate-700 mb-2 flex items-center">
                        <i class="fa-regular fa-calendar-check mr-2 text-blue-500"></i> Sampai Tanggal
                    </label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-semibold text-slate-700 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition-all">
                </div>
                <div class="w-full md:w-1/5">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-blue-600/30 border border-blue-500 flex justify-center items-center">
                        <i class="fa-solid fa-filter mr-2"></i> Filter Data
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-blue-800 p-8 rounded-3xl shadow-xl shadow-blue-600/30 text-white relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110 duration-500">
                    <i class="fa-solid fa-coins text-[10rem]"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-blue-100 font-bold mb-2 uppercase tracking-wider text-sm">Total Pendapatan</p>
                    <h3 class="text-4xl md:text-5xl font-black mb-4">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <div class="inline-flex items-center text-xs font-semibold text-blue-100 bg-blue-900/40 border border-blue-500/50 px-3 py-1.5 rounded-full backdrop-blur-sm">
                        <i class="fa-regular fa-clock mr-1.5"></i> Periode: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col justify-center relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 duration-500">
                    <i class="fa-solid fa-receipt text-[10rem] text-sky-500"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-slate-400 font-bold mb-2 uppercase tracking-wider text-sm">Jumlah Transaksi Selesai</p>
                    <h3 class="text-4xl md:text-5xl font-black text-slate-800">{{ $totalOrders }} <span class="text-2xl text-slate-400 font-bold ml-1">Pesanan</span></h3>
                    <div class="mt-4 inline-flex items-center text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-full">
                        <i class="fa-solid fa-check-circle mr-1.5"></i> Sukses Terbayar
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="font-extrabold text-slate-800 text-base tracking-wide flex items-center">
                    <div class="bg-blue-100 text-blue-600 p-2.5 rounded-xl mr-3">
                        <i class="fa-solid fa-table-list"></i>
                    </div>
                    Rincian Transaksi
                </h2>
            </div>
            <div class="overflow-x-auto p-2">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-400 text-xs uppercase tracking-wider font-black">
                            <th class="p-5 border-b border-slate-100">Waktu</th>
                            <th class="p-5 border-b border-slate-100">Kode Nota</th>
                            <th class="p-5 border-b border-slate-100">Pelanggan</th>
                            <th class="p-5 border-b border-slate-100">Tipe Pesanan</th>
                            <th class="p-5 border-b border-slate-100 text-right">Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-blue-50/40 transition-colors group">
                            <td class="p-5">
                                <div class="font-bold text-slate-700 text-sm">{{ $order->updated_at->format('d M Y') }}</div>
                                <div class="text-xs font-semibold text-slate-400 mt-1 flex items-center"><i class="fa-regular fa-clock mr-1.5 text-blue-400"></i> {{ $order->updated_at->format('H:i') }}</div>
                            </td>
                            <td class="p-5">
                                <span class="font-mono text-xs font-extrabold text-blue-700 bg-blue-100/50 px-3 py-1.5 rounded-lg border border-blue-200 shadow-sm">{{ $order->order_code }}</span>
                            </td>
                            <td class="p-5 text-sm font-bold text-slate-700">
                                {{ $order->customer_name }}
                            </td>
                            <td class="p-5">
                                @if($order->order_type == 'Take Away')
                                    <span class="bg-orange-50 text-orange-600 border border-orange-100 px-3 py-1.5 rounded-full text-xs font-bold shadow-sm flex inline-flex items-center w-max"><i class="fa-solid fa-bag-shopping mr-1.5"></i> Take Away</span>
                                @else
                                    <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1.5 rounded-full text-xs font-bold shadow-sm flex inline-flex items-center w-max"><i class="fa-solid fa-utensils mr-1.5"></i> Meja {{ $order->table->table_number ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="p-5 text-right">
                                <span class="text-base font-black text-slate-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-16 text-center">
                                <div class="bg-slate-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100 shadow-sm">
                                    <i class="fa-solid fa-magnifying-glass text-slate-300 text-4xl"></i>
                                </div>
                                <h3 class="text-slate-600 font-extrabold text-lg mb-2">Tidak Ada Data</h3>
                                <p class="text-slate-400 text-sm font-medium">Tidak ada transaksi yang ditemukan pada rentang tanggal yang dipilih.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>



