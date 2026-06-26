<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Z-Report (Tutup Kasir)</title>
    <style>
        /* Gaya khusus struk thermal printer bluetooth (lebar 58mm) */
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            font-size: 12px; 
            color: #000; 
            padding: 2mm; 
            width: 58mm; /* Cocok untuk 58mm printer bluetooth */
            max-width: 58mm;
            margin: 0 auto;
        }
        h1, h2, h3, p { margin: 0; padding: 0; text-align: center; }
        .header { margin-bottom: 15px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .header h1 { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .info { text-align: left; margin-bottom: 10px; font-size: 11px; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th, td { padding: 3px 0; text-align: left; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .summary-box { border: 1px solid #000; padding: 10px; margin-top: 15px; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; border-top: 1px dashed #000; padding-top: 10px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #000; color: #fff; border: none; cursor: pointer; font-weight: bold;">Cetak Struk Thermal</button>
    </div>

    <div class="header">
        <h1>Terralog Coffee N Eatery</h1>
        <p>Jl. Aman I No.2, Teladan Barat, Medan Kota, Medan</p>
    </div>

    <div class="info">
        <table style="width: 100%;">
            <tr>
                <td>Laporan</td>
                <td>: TUTUP KASIR (Z-REPORT)</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ now()->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>Waktu Cetak</td>
                <td>: {{ now()->format('H:i:s') }}</td>
            </tr>
            <tr>
                <td>Kasir Shift</td>
                <td>: {{ Auth::user()->name }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>
    <p style="font-weight: bold; text-align:left;">RINGKASAN PESANAN</p>
    <div class="divider"></div>

    <table>
        <tr>
            <td>Dine In (Makan di Tempat)</td>
            <td class="text-right">{{ $dineInCount }} Nota</td>
        </tr>
        <tr>
            <td>Take Away (Bawa Pulang)</td>
            <td class="text-right">{{ $takeAwayCount }} Nota</td>
        </tr>
        <tr style="border-top: 1px dotted #000;">
            <td class="bold">Total Nota Hari Ini</td>
            <td class="text-right bold">{{ $totalOrders }} Nota</td>
        </tr>
    </table>

    <div class="divider"></div>
    <p style="font-weight: bold; text-align:left;">RINGKASAN KEUANGAN</p>
    <div class="divider"></div>

    <table>
        <tr>
            <td>Total Penjualan</td>
            <td class="text-right">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    <p style="font-weight: bold; text-align:left;">METODE PEMBAYARAN</p>
    <div class="divider"></div>

    <table>
        <tr>
            <td>Pembayaran QRIS</td>
            <td class="text-right">Rp {{ number_format($totalQRIS, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Pembayaran Tunai</td>
            <td class="text-right">Rp {{ number_format($totalTunai, 0, ',', '.') }}</td>
        </tr>
        <tr style="border-top: 1px dotted #000;">
            <td class="bold">Total Uang Diterima (Tunai)</td>
            <td class="text-right">Rp {{ number_format($totalAmountPaidTunai, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="bold">Total Uang Kembali (Tunai)</td>
            <td class="text-right">-Rp {{ number_format($totalAmountReturnTunai, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="summary-box">
        <table style="width: 100%;">
            <tr>
                <td style="font-size: 14px; font-weight: bold;">TOTAL PENDAPATAN</td>
            </tr>
            <tr>
                <td class="text-right" style="font-size: 16px; font-weight: bold;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2"><hr style="border: 0; border-top: 1px dashed #000; margin: 5px 0;"></td>
            </tr>
            <tr>
                <td style="font-size: 14px; font-weight: bold;">TOTAL UANG TUNAI DI LACI</td>
            </tr>
            <tr>
                <td class="text-right" style="font-size: 16px; font-weight: bold;">Rp {{ number_format($totalTunai, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Saya menyatakan bahwa laporan penjualan</p>
        <p>ini dicetak sesuai dengan keadaan</p>
        <p>sistem dan jumlah total uang masuk hari ini.</p>
        <br><br><br>
        <p>( {{ Auth::user()->name }} )</p>
        <p>Tanda Tangan Kasir</p>
    </div>

</body>
</html>

