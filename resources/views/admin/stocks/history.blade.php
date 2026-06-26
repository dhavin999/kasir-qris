@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Riwayat Stok: {{ $menu->name }}</h2>
            <p class="text-sm text-gray-500">Kategori: {{ $menu->category->name }} | Stok Saat Ini: <span class="font-bold text-blue-600">{{ $menu->stock ? $menu->stock->current_stock : 0 }} porsi</span></p>
        </div>
        <a href="{{ route('stocks.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm font-semibold border-b">
                    <th class="p-4 w-16">No</th>
                    <th class="p-4">Tanggal & Waktu</th>
                    <th class="p-4">Tipe</th>
                    <th class="p-4 text-center">Jumlah</th>
                    <th class="p-4">Keterangan</th>
                    <th class="p-4">Oleh</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y">
                @if($menu->stock && $menu->stock->histories->count() > 0)
                    @foreach($menu->stock->histories as $index => $history)
                        <tr>
                            <td class="p-4 font-medium">{{ $index + 1 }}</td>
                            <td class="p-4">{{ $history->created_at->format('d M Y, H:i') }}</td>
                            <td class="p-4">
                                @if($history->type === 'Masuk' || $history->type === 'in')
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded border border-green-200 font-medium"><i class="fa-solid fa-arrow-down mr-1"></i> Masuk</span>
                                @else
                                    <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded border border-red-200 font-medium"><i class="fa-solid fa-arrow-up mr-1"></i> Keluar</span>
                                @endif
                            </td>
                            <td class="p-4 text-center font-bold">
                                {{ $history->type === 'Masuk' || $history->type === 'in' ? '+' : '-' }}{{ $history->quantity }}
                            </td>
                            <td class="p-4 text-gray-600 italic">
                                {{ $history->reference ?? '-' }}
                            </td>
                            <td class="p-4">
                                {{ $history->user->name ?? 'Sistem' }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
                            Belum ada riwayat perubahan stok untuk menu ini.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

