<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('table_number', 'asc')->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|max:10|unique:tables,table_number', // max 10 sesuai panjang di migration-mu
        ]);

        Table::create([
            'table_number' => $request->table_number,
            'status' => 'Kosong', 
            'last_cleared_at' => now(),
        ]);

        return redirect()->route('tables.index')->with('success', 'Meja baru berhasil ditambahkan!');
    }

    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'table_number' => 'required|string|max:10|unique:tables,table_number,' . $table->id,
            'status' => 'required|in:Kosong,Terisi', // 🔄 Validasi sesuai isi Enum database kamu
        ]);

        $data = [
            'table_number' => $request->table_number,
            'status' => $request->status,
        ];

        if ($request->status === 'Kosong' && $table->status !== 'Kosong') {
            $data['last_cleared_at'] = now();
        }

        $table->update($data);

        return redirect()->route('tables.index')->with('success', 'Data meja berhasil diperbarui!');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Meja berhasil dihapus!');
    }
}
