<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <title>Cetak Nota #{{ $order->order_code }}</title>
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 58mm; /* Ukuran kertas thermal printer bluetooth 58mm */
            max-width: 58mm;
            margin: 0 auto;
            padding: 2mm;
            font-size: 12px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-top: 1px dashed #000; margin: 8px 0; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 2px; }
        .item-row { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body onload="window.print(); window.onafterprint = function(){ window.close(); }">

    <div class="text-center">
        <div class="title">Terralog Coffee N Eatery</div>
        <div>Jl. Aman I No.2, Teladan Barat, Medan Kota, Medan</div>
    </div>

    <div class="line"></div>

    <table>
        <tr><td>Nota:</td><td class="text-right">{{ $order->order_code }}</td></tr>
        <tr><td>Tgl :</td><td class="text-right">{{ $order->created_at->format('d/m/Y H:i') }}</td></tr>
        <tr><td>Meja:</td><td class="text-right">No. {{ $order->table->table_number ?? '-' }}</td></tr>
        <tr><td>Nama:</td><td class="text-right">{{ $order->customer_name }}</td></tr>
    </table>

    <div class="line"></div>

    <table>
        @foreach($order->items as $item)
            <tr class="item-row">
                <td colspan="2">{{ $item->menu->name }}</td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr><td>Subtotal:</td><td class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
        @if($order->discount_amount > 0)
            <tr><td>Diskon ({{ $order->promo->code ?? 'Kupon' }}):</td><td class="text-right">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td></tr>
        @endif
        <tr style="font-weight: bold;"><td>TOTAL BAYAR:</td><td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td></tr>
    </table>

    <div class="line"></div>

    @if($order->payment)
    <table>
        <tr><td>Metode:</td><td class="text-right">{{ $order->payment->payment_method }}</td></tr>
        <tr><td>Dibayar:</td><td class="text-right">Rp {{ number_format($order->payment->amount_paid, 0, ',', '.') }}</td></tr>
        <tr><td>Kembali:</td><td class="text-right">Rp {{ number_format($order->payment->amount_return, 0, ',', '.') }}</td></tr>
    </table>

    <div class="line"></div>
    @endif

    <div class="text-center" style="margin-top: 15px;">
        *** TERIMA KASIH ***<br>
        Selamat Menikmati Hidangan Anda
    </div>

</body>
</html>
