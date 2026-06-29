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
        
        .no-print {
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            width: max-content;
            margin-left: auto;
            margin-right: auto;
        }
        .btn {
            padding: 8px 12px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            color: #fff;
            font-family: Arial, sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-blue { background: #007bff; }
        .btn-blue:hover { background: #0056b3; }
        .btn-gray { background: #6c757d; }
        .btn-gray:hover { background: #5a6268; }
        .btn-red { background: #dc3545; }
        .btn-red:hover { background: #c82333; }
        
        @media print {
            .no-print { display: none !important; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="no-print">
        <button onclick="printBluetooth()" class="btn btn-blue">
            <i class="fab fa-bluetooth"></i> Cetak Bluetooth
        </button><br>
        <button onclick="window.print()" class="btn btn-gray">
            <i class="fas fa-print"></i> Cetak Standar
        </button>
        <button onclick="window.close()" class="btn btn-red">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

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

    <script>
        const ESC = 0x1B;
        const GS = 0x1D;
        const LF = 0x0A;

        function generateReceiptData() {
            let data = [];
            const encoder = new TextEncoder();

            function addBytes(...bytes) {
                data.push(...bytes);
            }

            function addText(text) {
                const bytes = encoder.encode(text);
                for(let i=0; i<bytes.length; i++) data.push(bytes[i]);
            }

            function alignLeft() { addBytes(ESC, 0x61, 0x00); }
            function alignCenter() { addBytes(ESC, 0x61, 0x01); }
            function alignRight() { addBytes(ESC, 0x61, 0x02); }
            function boldOn() { addBytes(ESC, 0x45, 0x01); }
            function boldOff() { addBytes(ESC, 0x45, 0x00); }
            function newLine() { addBytes(LF); }

            function formatLine(left, right, width = 32) {
                let leftStr = String(left);
                let rightStr = String(right);
                let len = leftStr.length + rightStr.length;
                if (len < width) {
                    return leftStr + ' '.repeat(width - len) + rightStr;
                } else {
                    return leftStr + ' ' + rightStr;
                }
            }

            // --- Mulai Format ESC/POS ---
            addBytes(ESC, 0x40); // Initialize
            
            alignCenter();
            boldOn();
            addText("*** PESANAN DAPUR ***");
            newLine();
            boldOff();
            
            alignLeft();
            addText("-".repeat(32)); newLine();
            
            boldOn(); addText(formatLine("Nota:", "{{ $order->order_code }}")); boldOff(); newLine();
            addText(formatLine("Jam :", "{{ $order->created_at->format('d/m/Y H:i') }}")); newLine();
            boldOn();
            addText(formatLine("Meja:", "@if($order->order_type === 'Take Away')TAKE AWAY@else No. {{ $order->table->table_number ?? '-' }}@endif"));
            boldOff(); newLine();
            addText(formatLine("Pemesan:", "{{ $order->customer_name }}")); newLine();
            
            addText("-".repeat(32)); newLine();
            
            addText(formatLine("Item", "Qty")); newLine();
            addText("-".repeat(32)); newLine();
            
            @foreach($order->items as $item)
                boldOn();
                // We format the item name to leave 4 spaces for Qty
                addText(formatLine("{{ $item->menu->name }}", "{{ $item->quantity }}")); newLine();
                boldOff();
                @if($item->notes)
                    addText("  - Cttn: {{ $item->notes }}"); newLine();
                @endif
            @endforeach
            
            addText("-".repeat(32)); newLine();
            alignCenter();
            addText("Segera disiapkan!"); newLine();
            
            addBytes(ESC, 0x64, 0x05); // Feed paper 5 lines
            
            return new Uint8Array(data);
        }

        async function printBluetooth() {
            try {
                if (!navigator.bluetooth) {
                    alert('Browser Anda tidak mendukung Web Bluetooth API. Harap gunakan Google Chrome atau Edge.');
                    return;
                }

                const device = await navigator.bluetooth.requestDevice({
                    acceptAllDevices: true,
                    optionalServices: [
                        '000018f0-0000-1000-8000-00805f9b34fb',
                        'e7810a71-73ae-499d-8c15-faa9aef0c3f2',
                        '0000fee7-0000-1000-8000-00805f9b34fb',
                        '49535343-fe7d-4ae5-8fa9-9fafd205e455',
                        '0000ff00-0000-1000-8000-00805f9b34fb' 
                    ]
                });

                const server = await device.gatt.connect();
                const services = await server.getPrimaryServices();
                
                let printCharacteristic = null;
                for (const service of services) {
                    const characteristics = await service.getCharacteristics();
                    for (const char of characteristics) {
                        if (char.properties.writeWithoutResponse || char.properties.write) {
                            printCharacteristic = char;
                            break;
                        }
                    }
                    if (printCharacteristic) break;
                }

                if (!printCharacteristic) {
                    alert('Gagal menemukan karakteristik komunikasi pada printer ini.');
                    return;
                }

                const data = generateReceiptData();
                const CHUNK_SIZE = 100;
                
                for (let i = 0; i < data.length; i += CHUNK_SIZE) {
                    const chunk = data.slice(i, i + CHUNK_SIZE);
                    await printCharacteristic.writeValue(chunk);
                }
                
                alert('Berhasil mengirim data pesanan dapur ke printer!');
            } catch (error) {
                console.error('Error saat mencetak:', error);
                alert('Gagal mencetak: ' + error.message);
            }
        }
    </script>
</body>
</html>

