<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <title>Cetak Pesanan Dapur #{{ $order->order_code }}</title>
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 58mm; /* Lebar standar printer thermal bluetooth 58mm */
            max-width: 58mm;
            margin: 0 auto;
            padding: 2mm;
            font-size: 14px; /* Ukuran font lebih besar sedikit agar mudah dibaca dapur */
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-top: 2px dashed #000; margin: 8px 0; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 2px; }
        .item-row { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; }
        .fw-bold { font-weight: bold; }
        .fs-large { font-size: 16px; }
        .notes { font-size: 12px; font-style: italic; margin-left: 10px; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body onload="window.print(); window.onafterprint = function(){ window.close(); }">

    <div class="text-center">
        <div class="title">*** PESANAN DAPUR ***</div>
    </div>

    <div class="line"></div>

    <table>
        <tr><td class="fw-bold">Nota:</td><td class="text-right fw-bold">{{ $order->order_code }}</td></tr>
        <tr><td>Jam :</td><td class="text-right">{{ $order->created_at->format('d/m/Y H:i') }}</td></tr>
        <tr>
            <td class="fw-bold fs-large">Meja:</td>
            <td class="text-right fw-bold fs-large">
                @if($order->order_type === 'Take Away')
                    TAKE AWAY
                @else
                    No. {{ $order->table->table_number ?? '-' }}
                @endif
            </td>
        </tr>
        <tr><td>Pemesan:</td><td class="text-right">{{ $order->customer_name }}</td></tr>
    </table>

    <div class="line"></div>

    <table style="margin-bottom: 10px;">
        <thead>
            <tr>
                <th style="text-align: left; border-bottom: 1px solid #000; padding-bottom: 5px;">Item</th>
                <th style="text-align: center; border-bottom: 1px solid #000; padding-bottom: 5px;">Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr class="item-row">
                    <td style="padding-top: 5px;" class="fw-bold">{{ $item->menu->name }}</td>
                    <td style="text-align: center; padding-top: 5px;" class="fw-bold fs-large">{{ $item->quantity }}</td>
                </tr>
                @if($item->notes)
                <tr>
                    <td colspan="2" class="notes">- Catatan: {{ $item->notes }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <div class="text-center" style="margin-top: 15px;">
        Segera disiapkan!
    </div>

</body>
</html>

