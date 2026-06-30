@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Sesuaikan Stok</h2>
            <p class="text-sm text-gray-500">Ubah jumlah stok untuk menu terpilih.</p>
        </div>
        <a href="{{ route('stocks.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
        <p class="text-sm text-blue-800 font-semibold mb-1">Menu: <span class="font-bold">{{ $menu->name }}</span></p>
        <p class="text-sm text-blue-800">Stok Saat Ini: <span class="font-bold bg-white px-2 py-1 rounded shadow-sm ml-1 text-indigo-600">{{ $menu->stock ? $menu->stock->current_stock : 0 }}</span> porsi</p>
    </div>

    <form action="{{ route('stocks.update', $menu->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-5">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Jenis Aksi</label>
            <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                <option value="in">➕ Tambah Estimasi Porsi (Belanja Bahan Baku Baru)</option>
                <option value="out">➖ Kurangi Estimasi Porsi (Bahan Baku Tumpah/Rusak, Kesalahan Dapur)</option>
            </select>
        </div>

        <div class="mb-5">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Jumlah Porsi</label>
            <input type="number" name="quantity" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: 20" min="1" required>
        </div>

        <div class="mb-8">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Keterangan / Alasan (Opsional)</label>
            <input type="text" name="notes" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Pasokan baru dari gudang / Es batu cair">
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('stocks.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-4 rounded-xl transition text-sm">
                Batal
            </a>
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md shadow-blue-500/20 text-sm">
                Simpan Perubahan Stok
            </button>
        </div>
    </form>
</div>
@endsection
