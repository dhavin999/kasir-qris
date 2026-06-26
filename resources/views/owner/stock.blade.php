<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <title>Monitoring Stok Kritis - Owner</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 p-8 min-h-screen">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-sky-500 rounded-3xl p-8 shadow-xl shadow-blue-500/20 text-white relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-10">
                <i class="fa-solid fa-boxes-stacked text-[12rem] -mt-10 -mr-10"></i>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h2 class="text-3xl font-black flex items-center mb-2">
                        <i class="fa-solid fa-triangle-exclamation text-yellow-400 mr-4"></i> Monitoring Stok Kritis
                    </h2>
                    <p class="text-blue-100 font-medium">Peringatan otomatis untuk bahan baku dan hidangan yang hampir habis.</p>
                </div>
                <a href="{{ route('owner.dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl font-bold transition-all shadow-sm flex items-center backdrop-blur-sm">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center">
                <div class="bg-red-100 text-red-600 p-2.5 rounded-xl mr-4 shadow-sm">
                    <i class="fa-solid fa-list-check text-lg"></i>
                </div>
                <h3 class="font-extrabold text-slate-800 text-lg">Daftar Menu Butuh Restock</h3>
            </div>
            
            <div class="overflow-x-auto p-2">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-white">
                        <tr class="text-slate-400 text-xs uppercase font-black tracking-wider">
                            <th class="p-5 border-b border-slate-100">Nama Menu</th>
                            <th class="p-5 border-b border-slate-100">Kategori</th>
                            <th class="p-5 border-b border-slate-100">Sisa Stok Saat Ini</th>
                            <th class="p-5 border-b border-slate-100">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($lowStocks as $menu)
                            <tr class="hover:bg-red-50/40 transition-colors group">
                                <td class="p-5">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center mr-4 border border-slate-200 shadow-sm group-hover:bg-white transition-colors">
                                            <i class="fa-solid fa-utensils"></i>
                                        </div>
                                        <span class="font-bold text-slate-800 text-base">{{ $menu->name }}</span>
                                    </div>
                                </td>
                                <td class="p-5 font-semibold text-slate-500">
                                    {{ $menu->category->name ?? 'Tanpa Kategori' }}
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full bg-red-500 mr-2 animate-pulse"></div>
                                        <span class="font-mono font-black text-red-600 text-lg">{{ $menu->stock->current_stock ?? 0 }} <span class="text-sm text-red-400 ml-1">porsi</span></span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <span class="bg-red-50 text-red-600 border border-red-200 text-xs font-black px-3 py-1.5 rounded-full shadow-sm flex inline-flex items-center w-max">
                                        <i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Wajib Restock
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-16 text-center">
                                    <div class="bg-emerald-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-5 border border-emerald-100 shadow-sm">
                                        <i class="fa-regular fa-face-smile text-emerald-400 text-5xl"></i>
                                    </div>
                                    <h3 class="text-slate-700 font-extrabold text-xl mb-2">Semua Stok Aman!</h3>
                                    <p class="text-slate-500 font-medium">Persediaan stok bahan baku dan menu melimpah, siap melayani pelanggan.</p>
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


