@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
    <p class="text-gray-500 mt-2 text-sm">Berikut adalah ringkasan data operasional tokomu hari ini.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <a href="{{ route('categories.index') }}" class="bg-white p-6 rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 flex items-center space-x-5 hover:-translate-y-1 hover:border-blue-300 hover:shadow-blue-200/50 transition-all duration-300 group">
        <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl shadow-inner border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">
            <i class="fa-solid fa-list"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-semibold mb-1 group-hover:text-blue-600 transition-colors">Kategori Menu</p>
            <p class="text-2xl font-black text-gray-900">{{ $categoryCount }} Kategori</p>
        </div>
    </a>
    
    <a href="{{ route('menus.index') }}" class="bg-white p-6 rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 flex items-center space-x-5 hover:-translate-y-1 hover:border-rose-300 hover:shadow-rose-200/50 transition-all duration-300 group">
        <div class="w-14 h-14 bg-gradient-to-br from-rose-100 to-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-2xl shadow-inner border border-rose-100 group-hover:bg-rose-600 group-hover:text-white transition-colors">
            <i class="fa-solid fa-utensils"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-semibold mb-1 group-hover:text-rose-600 transition-colors">Manajemen Menu</p>
            <p class="text-2xl font-black text-gray-900">{{ $menuCount }} Menu</p>
        </div>
    </a>
    
    <a href="{{ route('stocks.index') }}" class="bg-white p-6 rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 flex items-center space-x-5 hover:-translate-y-1 hover:border-indigo-300 hover:shadow-indigo-200/50 transition-all duration-300 group">
        <div class="w-14 h-14 bg-gradient-to-br from-indigo-100 to-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-2xl shadow-inner border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
            <i class="fa-solid fa-boxes-stacked"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-semibold mb-1 group-hover:text-indigo-600 transition-colors">Stok Barang</p>
            <p class="text-2xl font-black text-gray-900">{{ $menuCount }} Menu</p>
        </div>
    </a>

    <a href="{{ route('users.index') }}" class="bg-white p-6 rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 flex items-center space-x-5 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-emerald-200/50 transition-all duration-300 group">
        <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-2xl shadow-inner border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-semibold mb-1 group-hover:text-emerald-600 transition-colors">Kelola User</p>
            <p class="text-2xl font-black text-gray-900">{{ $userCount }} Akun</p>
        </div>
    </a>
</div>

@if(isset($lowStockMenus) && $lowStockMenus->count() > 0)
<div class="mt-8">
    <div class="bg-red-50 border border-red-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-lg">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-lg font-bold text-red-800">Peringatan Stok Menipis</h3>
        </div>
        <p class="text-sm text-red-700 mb-4">Berikut adalah daftar menu yang stoknya hampir habis (kurang dari atau sama dengan 10 porsi). Segera lakukan penambahan stok.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($lowStockMenus as $menu)
            <div class="bg-white rounded-xl p-4 shadow-sm border border-red-100 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        @if($menu->image)
                            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-image"></i></div>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">{{ $menu->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $menu->category->name }}</p>
                    </div>
                </div>
                <div class="text-right flex flex-col items-end">
                    @if($menu->stock && $menu->stock->current_stock == 0)
                        <span class="bg-red-600 text-white font-bold px-3 py-1 rounded-full text-xs animate-pulse">Habis (0)</span>
                    @else
                        <span class="bg-amber-100 text-amber-700 font-bold px-3 py-1 rounded-full text-xs border border-amber-200">Sisa {{ $menu->stock->current_stock }}</span>
                    @endif
                    <a href="{{ route('stocks.edit', $menu->id) }}" class="text-xs text-blue-600 hover:text-blue-800 mt-2 font-medium">Update Stok <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
