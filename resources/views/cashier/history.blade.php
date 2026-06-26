<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="max-w-4xl mx-auto p-6">
        <div class="flex items-center space-x-3 mb-6">
            <a href="{{ route('kasir.dashboard') }}" class="bg-white border text-gray-700 hover:bg-gray-100 font-bold px-3 py-1.5 rounded-xl text-sm transition">&larr; Kembali</a>
            <h2 class="text-xl font-black">Arsip Transaksi Penjualan</h2>
        </div>

        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b text-slate-500 font-bold">
                        <th class="p-4">Waktu</th>
                        <th class="p-4">Kode Nota</th>
                        <th class="p-4">Meja</th>
                        <th class="p-4">Nama Pelanggan</th>
                        <th class="p-4">Total Bayar</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($completedOrders as $order)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="p-4 text-xs text-gray-400 font-mono">{{ $order->updated_at->format('d M Y, H:i') }}</td>
                            <td class="p-4 font-mono font-bold text-gray-700">{{ $order->order_code }}</td>
                            <td class="p-4 font-bold text-gray-900">Meja {{ $order->table->table_number ?? '-' }}</td>
                            <td class="p-4 text-gray-600">{{ $order->customer_name }}</td>
                            <td class="p-4 font-black text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border 
                                    {{ $order->status === 'Selesai' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('kasir.printReceipt', $order->id) }}" target="_blank" class="text-xs bg-slate-100 hover:bg-slate-200 font-bold px-3 py-1.5 rounded-lg text-slate-700 transition">
                                    <i class="fa-solid fa-print"></i> Re-Print
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400 italic">Belum ada data transaksi yang diselesaikan hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="p-4 bg-slate-50 border-t">
                {{ $completedOrders->links() }}
            </div>
        </div>
    </div>

</body>
</html>
