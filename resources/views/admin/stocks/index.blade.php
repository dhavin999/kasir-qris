@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Manajemen Stok Menu</h2>
        <p class="text-sm text-gray-500">Pantau dan sesuaikan ketersediaan porsi menu makanan atau minuman.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm font-semibold border-b">
                    <th class="p-4 w-16">No</th>
                    <th class="p-4">Menu</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4 text-center">Stok Saat Ini</th>
                    <th class="p-4 w-40 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y">
                @foreach($menus as $index => $menu)
                    <tr>
                        <td class="p-4 font-medium">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 border overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    @if($menu->image)
                                        <img src="{{ asset('storage/' . $menu->image) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-lg">🍽️</span>
                                    @endif
                                </div>
                                <div class="font-semibold text-gray-800">{{ $menu->name }}</div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded border">{{ $menu->category->name }}</span>
                        </td>
                        <td class="p-4 text-center">
                            @php $qty = $menu->stock ? $menu->stock->current_stock : 0; @endphp
                            @if($qty == 0)
                                <span class="bg-red-100 text-red-700 font-bold px-3 py-1 rounded-full text-xs">0</span>
                            @elseif($qty <= 10)
                                <span class="bg-amber-100 text-amber-700 font-bold px-3 py-1 rounded-full text-xs">{{ $qty }}</span>
                            @else
                                <span class="bg-green-100 text-green-700 font-bold px-3 py-1 rounded-full text-xs">{{ $qty }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <button onclick="openStockModal({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $qty }})" class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-md transition border border-blue-200 shadow-sm" title="Sesuaikan Stok">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <a href="{{ route('stocks.history', $menu->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-50 text-gray-600 hover:bg-gray-100 rounded-md transition border border-gray-200 shadow-sm" title="Riwayat Stok">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Update Stok -->
<div id="stockModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 p-6 w-full max-w-md m-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Sesuaikan Stok</h3>
            <button onclick="closeStockModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <div class="mb-4 bg-blue-50 p-3 rounded-lg border border-blue-100">
            <p class="text-xs text-blue-800 font-semibold mb-1">Menu: <span id="modalMenuName" class="font-bold"></span></p>
            <p class="text-xs text-blue-800">Stok Saat Ini: <span id="modalCurrentStock" class="font-bold bg-white px-2 py-0.5 rounded shadow-sm ml-1"></span> porsi</p>
        </div>

        <form id="stockForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Jenis Aksi</label>
                <select name="type" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="out">➖ Kurangi Estimasi Porsi (Bahan Baku Tumpah/Rusak, Kesalahan Dapur)</option>
                    <option value="in"><i class="fa-solid fa-plus"></i> Tambah Estimasi Porsi (Belanja Bahan Baku Baru)</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Jumlah Porsi</label>
                <input type="number" name="quantity" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 20" min="1" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Keterangan / Alasan (Opsional)</label>
                <input type="text" name="notes" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Pasokan baru dari gudang / Es batu cair">
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeStockModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 px-4 rounded-lg transition text-sm">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openStockModal(id, menuName, currentStock) {
        document.getElementById('stockModal').classList.remove('hidden');
        document.getElementById('modalMenuName').innerText = menuName;
        document.getElementById('modalCurrentStock').innerText = currentStock;
        document.getElementById('stockForm').action = "/admin/stocks/" + id;
    }

    function closeStockModal() {
        document.getElementById('stockModal').classList.add('hidden');
    }
</script>
@endsection
