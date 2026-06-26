@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Manajemen Pengguna / Karyawan</h2>
            <p class="text-sm text-gray-500">Kelola hak akses akun untuk Admin, Kasir, dan Owner Kafe.</p>
        </div>
        <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
            <i class="fa-solid fa-plus"></i> Tambah Karyawan
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
                    <th class="p-4">Nama Lengkap</th>
                    <th class="p-4">Email</th>
                    <th class="p-4 text-center">Role / Jabatan</th>
                    <th class="p-4 w-48 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y">
                @forelse($users as $index => $user)
                    <tr>
                        <td class="p-4 font-medium">{{ $index + 1 }}</td>
                        <td class="p-4 font-bold text-gray-800">{{ $user->name }}</td>
                        <td class="p-4 text-gray-500">{{ $user->email }}</td>
                        <td class="p-4 text-center">
                            @if($user->role === 'Admin')
                                <span class="bg-purple-100 text-purple-700 px-2.5 py-1 rounded-full text-xs font-semibold">Admin</span>
                            @elseif($user->role === 'Kasir')
                                <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-semibold">Kasir</span>
                            @else
                                <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-xs font-semibold">Owner</span>
                            @endif
                        </td>
                        <td class="p-4 flex justify-center space-x-2 mt-1">
                            <button onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->role_id }}')" title="Edit" class="w-8 h-8 inline-flex items-center justify-center bg-amber-100 text-amber-700 hover:bg-amber-200 font-bold rounded-md transition text-xs"><i class="fa-solid fa-pen-to-square"></i></button>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus" class="w-8 h-8 inline-flex items-center justify-center bg-red-100 text-red-700 hover:bg-red-200 font-bold rounded-md transition text-xs"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400 italic">Belum ada data karyawan lain.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Karyawan -->
<div id="addModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 p-6 w-full max-w-md m-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Tambah Akun Karyawan</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Lengkap</label>
                <input type="text" name="name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Alamat Email</label>
                <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Minimal 8 karakter" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Hak Akses (Role)</label>
                <select name="role_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="1">Admin</option>
                    <option value="2">Kasir</option>
                    <option value="3">Owner</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeAddModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 px-4 rounded-lg transition text-sm">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Karyawan -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 p-6 w-full max-w-md m-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Edit Akun Karyawan</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="editName" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Alamat Email</label>
                <input type="email" name="email" id="editEmail" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Password Baru <span class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak ingin ganti)</span></label>
                <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Minimal 8 karakter">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Hak Akses (Role)</label>
                <select name="role_id" id="editRoleId" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="1">Admin</option>
                    <option value="2">Kasir</option>
                    <option value="3">Owner</option>
                </select>
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

    function openEditModal(id, name, email, roleId) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRoleId').value = roleId;
        document.getElementById('editForm').action = "/admin/users/" + id;
    }
</script>
@endsection


