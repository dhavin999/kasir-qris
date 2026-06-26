<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <title>Laporan Pendapatan Kafe</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { bg-color: #f2f2f2; }
        .text-right { text-align: right; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body onload="window.print()">
    <h2 style="margin-bottom: 2px;">LAPORAN REKAPITULASI PENDAPATAN KAFE</h2>
    <p style="margin-top:0; color:#666;">Dicetak pada: {{ date('d M Y H:i') }}</p>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Waktu</th><th>Kode Nota</th><th>Pelanggan</th><th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; background:#f9f9f9;">
                <td colspan="3" class="text-right">TOTAL OMSET BERSIH:</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>


