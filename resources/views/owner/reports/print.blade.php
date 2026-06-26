<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .header p { margin: 0; font-size: 14px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row th { font-size: 14px; background-color: #e9e9e9; }
        .footer { margin-top: 50px; text-align: right; font-size: 12px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #000; color: #fff; border: none; cursor: pointer;">Cetak Sekarang</button>
    </div>

    <div class="header">
        <h1>Terralog Coffee N Eatery</h1>
        <p>Laporan Penjualan Periode: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Waktu Selesai</th>
                <th width="20%">Kode Nota</th>
                <th width="20%">Pelanggan</th>
                <th width="15%">Tipe</th>
                <th width="25%" class="text-right">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->order_type ?? 'Dine In' }}</td>
                <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 30px;">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="5" class="text-right">TOTAL PENDAPATAN</th>
                <th class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d M Y, H:i') }}</p>
        <p>Oleh: {{ Auth::user()->name }} ({{ Auth::user()->role->name ?? 'Admin' }})</p>
    </div>

</body>
</html>



