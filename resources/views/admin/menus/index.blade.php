@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Menu Makanan & Minuman</h2>
        <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
            <i class="fa-solid fa-plus"></i> Tambah Menu
        </button>
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
                    <th class="p-4">Nama Menu</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 w-48 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y">
                @forelse($menus as $index => $menu)
                    <tr>
                        <td class="p-4 font-medium">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 border overflow-hidden flex-shrink-0 flex items-center justify-center">
                                 @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xl">🍽️</span>
                                @endif
                            </div>
                            <div>
                                    <div class="font-semibold text-gray-800">{{ $menu->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $menu->description ?? 'Tidak ada deskripsi' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-xs font-medium border">
                                {{ $menu->category->name }}
                            </span>
                        </td>
                        <td class="p-4 font-bold text-gray-800">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                        <td class="p-4">
                            @if($menu->is_available)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">Tersedia</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold">Habis</span>
                            @endif
                        </td>
                        <td class="p-4 flex justify-center space-x-2">
                            <button onclick="openEditModal({{ $menu->id }}, '{{ addslashes($menu->name) }}', '{{ $menu->category_id }}', '{{ $menu->price }}', '{{ addslashes($menu->description) }}', '{{ $menu->is_available }}')" title="Edit" class="w-8 h-8 inline-flex items-center justify-center bg-amber-100 text-amber-700 hover:bg-amber-200 font-bold rounded-md transition text-xs">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus" class="w-8 h-8 inline-flex items-center justify-center bg-red-100 text-red-700 hover:bg-red-200 font-bold rounded-md transition text-xs">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400 italic">Belum ada data menu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Menu -->
<div id="addModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 p-6 w-full max-w-xl m-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Tambah Menu Baru</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Kategori Menu</label>
                <select name="category_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Menu</label>
                <input type="text" name="name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Ayam Bakar Madu" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Harga (Rp)</label>
                <input type="number" name="price" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 20000" min="0" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Foto Menu</label>
                <input type="file" name="image" class="w-full px-3 py-2 border rounded-lg text-sm">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Status</label>
                <select name="is_available" class="w-full px-3 py-2 border rounded-lg">
                    <option value="1">Tersedia / Aktif</option>
                    <option value="0">Habis / Nonaktif</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeAddModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 px-4 rounded-lg">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Menu -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 p-6 w-full max-w-xl m-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Edit Menu</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Kategori Menu</label>
                <select name="category_id" id="editCategoryId" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Menu</label>
                <input type="text" name="name" id="editName" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Harga (Rp)</label>
                <input type="number" name="price" id="editPrice" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" min="0" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" id="editDescription" rows="2" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Foto Menu (Biarkan kosong jika tidak diubah)</label>
                <input type="file" name="image" class="w-full px-3 py-2 border rounded-lg text-sm">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Status</label>
                <select name="is_available" id="editStatus" class="w-full px-3 py-2 border rounded-lg">
                    <option value="1">Tersedia / Aktif</option>
                    <option value="0">Habis / Nonaktif</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeEditModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 px-4 rounded-lg">Batal</button>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() { document.getElementById('addModal').classList.remove('hidden'); }
    function closeAddModal() { document.getElementById('addModal').classList.add('hidden'); }
    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }

    function openEditModal(id, name, categoryId, price, desc, status) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editName').value = name;
        document.getElementById('editCategoryId').value = categoryId;
        document.getElementById('editPrice').value = price;
        document.getElementById('editDescription').value = desc;
        document.getElementById('editStatus').value = status;
        document.getElementById('editForm').action = "/admin/menus/" + id;
    }
</script>
@endsection
