<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner - terralog</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <i class="fa-solid fa-chart-pie text-xl"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-xl tracking-wide drop-shadow-md">Owner INSIGHTS</h1>
                    <p class="text-xs text-blue-100 font-medium mt-0.5">Data performa bisnis real-time</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('owner.reports.index') }}" class="bg-white/10 hover:bg-white/20 text-sm font-semibold px-4 py-2.5 rounded-xl backdrop-blur-sm transition-all border border-white/10 flex items-center shadow-sm">
                    <i class="fa-solid fa-file-invoice mr-2"></i> Laporan
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fa-solid fa-calendar-day text-7xl text-blue-500"></i>
                </div>
                <div class="relative z-10">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pendapatan Hari Ini</span>
                    <h3 class="text-3xl font-black text-blue-600 mt-2">Rp {{ number_format($todaySales, 0, ',', '.') }}</h3>
                    <div class="mt-4 flex items-center text-xs font-semibold text-emerald-600 bg-emerald-50 w-max px-3 py-1.5 rounded-full border border-emerald-100">
                        <i class="fa-solid fa-arrow-trend-up mr-1.5"></i> Live Data
                    </div>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fa-solid fa-calendar-week text-7xl text-sky-500"></i>
                </div>
                <div class="relative z-10">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pendapatan Bulan Ini</span>
                    <h3 class="text-3xl font-black text-sky-600 mt-2">Rp {{ number_format($monthSales, 0, ',', '.') }}</h3>
                    <div class="mt-4 flex items-center text-xs font-semibold text-blue-600 bg-blue-50 w-max px-3 py-1.5 rounded-full border border-blue-100">
                        <i class="fa-solid fa-chart-line mr-1.5"></i> Akumulasi
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-gradient-to-br from-blue-900 to-blue-800 p-6 rounded-3xl shadow-xl shadow-blue-900/30 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300 text-white border border-blue-700">
                <div class="relative z-10">
                    <span class="text-xs font-bold text-blue-300 uppercase tracking-wider">Total Tahun Ini</span>
                    <h3 class="text-3xl font-black text-white mt-2">Rp {{ number_format($yearSales, 0, ',', '.') }}</h3>
                    <div class="mt-4 flex items-center text-xs font-semibold text-blue-100 bg-blue-950/50 border border-blue-700 w-max px-3 py-1.5 rounded-full shadow-inner">
                        <i class="fa-solid fa-crown mr-1.5 text-yellow-400"></i> Performa Bisnis
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart Section -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 lg:col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-extrabold text-slate-800 text-base tracking-wide flex items-center">
                        <div class="bg-blue-100 text-blue-600 p-2.5 rounded-xl mr-3">
                            <i class="fa-solid fa-chart-area"></i>
                        </div>
                        Tren Omset 7 Hari Terakhir
                    </h2>
                </div>
                <div class="h-[320px] w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Top Menu Section -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col">
                <h2 class="font-extrabold text-slate-800 text-base tracking-wide flex items-center mb-6">
                    <div class="bg-orange-100 text-orange-500 p-2.5 rounded-xl mr-3">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                    5 Menu Terlaris
                </h2>
                <div class="space-y-3 flex-1">
                    @forelse($topMenus as $index => $item)
                        <div class="flex justify-between items-center p-3 hover:bg-slate-50 rounded-2xl transition-all duration-200 border border-transparent hover:border-slate-100 group">
                            <div class="flex items-center space-x-4">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm group-hover:scale-110 transition-transform
                                    {{ $index == 0 ? 'bg-gradient-to-br from-yellow-100 to-amber-200 text-amber-700' : 
                                       ($index == 1 ? 'bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600' : 
                                       ($index == 2 ? 'bg-gradient-to-br from-orange-50 to-orange-100 text-orange-600' : 'bg-blue-50 text-blue-600')) }}">
                                    #{{ $index+1 }}
                                </div>
                                <span class="text-sm font-bold text-slate-700">{{ $item->menu->name ?? 'Menu Terhapus' }}</span>
                            </div>
                            <span class="bg-blue-50/50 text-blue-700 border border-blue-100 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">{{ $item->total_sold }} Porsi</span>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-center py-8">
                            <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 shadow-sm">
                                <i class="fa-solid fa-utensils text-slate-300 text-3xl"></i>
                            </div>
                            <h3 class="text-slate-500 font-bold mb-1">Belum Ada Data</h3>
                            <p class="text-sm font-medium text-slate-400">Data penjualan menu akan tampil di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Latest Transactions -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white">
                <h2 class="font-extrabold text-slate-800 text-base tracking-wide flex items-center">
                    <div class="bg-emerald-100 text-emerald-600 p-2.5 rounded-xl mr-3">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    Rekap Log Transaksi Terbaru
                </h2>
                <a href="{{ route('owner.reports.index') }}" class="group bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-all duration-300 flex items-center border border-blue-100 hover:border-transparent shadow-sm">
                    Lihat Semua Transaksi 
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-500 text-xs uppercase font-extrabold tracking-wider">
                            <th class="p-5 whitespace-nowrap">Waktu Keluar</th>
                            <th class="p-5 whitespace-nowrap">Kode Nota</th>
                            <th class="p-5 whitespace-nowrap">Pelanggan</th>
                            <th class="p-5 whitespace-nowrap">Diskon</th>
                            <th class="p-5 whitespace-nowrap">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($orders as $order)
                            <tr class="hover:bg-blue-50/40 transition-colors group">
                                <td class="p-5 text-sm">
                                    <div class="font-bold text-slate-700">{{ $order->updated_at->format('d M Y') }}</div>
                                    <div class="text-xs font-semibold text-slate-400 mt-1 flex items-center"><i class="fa-regular fa-clock mr-1.5 text-blue-400"></i> {{ $order->updated_at->format('H:i') }}</div>
                                </td>
                                <td class="p-5">
                                    <span class="font-mono text-xs font-extrabold text-blue-700 bg-blue-100/50 px-3 py-1.5 rounded-lg border border-blue-200 shadow-sm">{{ $order->order_code }}</span>
                                </td>
                                <td class="p-5 font-bold text-slate-700">
                                    {{ $order->customer_name }}
                                </td>
                                <td class="p-5">
                                    @if($order->discount_amount > 0)
                                        <span class="text-red-600 font-bold bg-red-50 px-3 py-1.5 rounded-lg text-xs border border-red-100 shadow-sm">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-300 font-bold bg-slate-50 px-3 py-1 rounded-lg text-xs">-</span>
                                    @endif
                                </td>
                                <td class="p-5">
                                    <span class="font-black text-slate-900 text-base">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-16 text-center">
                                    <div class="bg-slate-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100 shadow-sm">
                                        <i class="fa-solid fa-file-invoice text-slate-300 text-4xl"></i>
                                    </div>
                                    <h3 class="text-slate-600 font-extrabold text-lg mb-2">Belum Ada Transaksi</h3>
                                    <p class="text-slate-400 text-sm font-medium">Transaksi yang telah lunas akan otomatis muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Create gradient for chart
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.4)'); // blue-600 with opacity
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->pluck('date')) !!},
                datasets: [{
                    label: 'Omset Penjualan (Rp)',
                    data: {!! json_encode($chartData->pluck('total')) !!},
                    borderColor: '#2563eb', // blue-600
                    backgroundColor: gradient,
                    borderWidth: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#2563eb',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3,
                    fill: true,
                    tension: 0.4 // smooth curves
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13, weight: 'bold' },
                        bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 15, weight: 'bold' },
                        padding: 14,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let value = context.raw || 0;
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: {
                            color: '#f8fafc', // slate-50
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", weight: '600' },
                            color: '#94a3b8', // slate-400
                            padding: 10
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", weight: '600' },
                            color: '#94a3b8',
                            padding: 10
                        },
                        border: { display: false }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    </script>
</body>
</html>


