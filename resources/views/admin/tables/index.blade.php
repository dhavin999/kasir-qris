@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Daftar Meja & QR Code</h2>
            <p class="text-sm text-gray-500">Kelola nomor meja dan unduh/cetak QR Code untuk pemesanan mandiri pelanggan.</p>
        </div>
        <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
            <i class="fa-solid fa-plus"></i> Tambah Meja
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
                    <th class="p-4">Nomor Meja</th>
                    <th class="p-4 text-center">QR Code Pemesanan</th>
                    <th class="p-4 text-center">Status Meja</th>
                    <th class="p-4 w-48 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y">
                @forelse($tables as $index => $table)
                    @php
                        $orderUrl = url('/order?meja=' . $table->table_number);
                        $qrCodeApi = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($orderUrl);
                    @endphp
                    <tr>
                        <td class="p-4 font-medium">{{ $index + 1 }}</td>
                        <td class="p-4 font-bold text-gray-800">Meja {{ $table->table_number }}</td>
                        <td class="p-4">
                            <div class="flex flex-col items-center justify-center space-y-1">
                                <img src="{{ $qrCodeApi }}" alt="QR" class="w-20 h-20 border p-1 bg-white rounded shadow-sm">
                                <a href="{{ $qrCodeApi }}&download=1" target="_blank" class="text-xs text-blue-600 hover:underline font-medium">📥 Download QR</a>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            @if($table->status === 'Kosong')
                                <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">Kosong</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold">Terisi</span>
                            @endif
                        </td>
                        <td class="p-4 flex justify-center space-x-2 mt-4">
                            <button onclick="openEditModal({{ $table->id }}, '{{ addslashes($table->table_number) }}')" title="Edit" class="w-8 h-8 inline-flex items-center justify-center bg-amber-100 text-amber-700 hover:bg-amber-200 font-bold rounded-md transition text-xs"><i class="fa-solid fa-pen-to-square"></i></button>
                            <form action="{{ route('tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Yakin menghapus meja?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                <button type="submit" title="Hapus" class="w-8 h-8 inline-flex items-center justify-center bg-red-100 text-red-700 hover:bg-red-200 font-bold rounded-md transition text-xs"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400 italic">Belum ada data meja.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Meja -->
<div id="addModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 p-6 w-full max-w-md m-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Tambah Meja Baru</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="{{ route('tables.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Nomor Meja</label>
                <input type="text" name="table_number" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 1, 2, VIP-1" required>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeAddModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 px-4 rounded-lg transition text-sm">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Meja -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 p-6 w-full max-w-md m-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Edit Nomor Meja</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Nomor Meja</label>
                <input type="text" name="table_number" id="editTableNumber" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeEditModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 px-4 rounded-lg transition text-sm">Batal</button>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg transition text-sm">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() { document.getElementById('addModal').classList.remove('hidden'); }
    function closeAddModal() { document.getElementById('addModal').classList.add('hidden'); }
    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }

    function openEditModal(id, tableNumber) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editTableNumber').value = tableNumber;
        document.getElementById('editForm').action = "/admin/tables/" + id;
    }
</script>
@endsection
