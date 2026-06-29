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
            addText("Terralog Coffee N Eatery");
            newLine();
            boldOff();
            addText("Jl. Aman I No.2, Teladan Barat, Medan Kota, Medan");
            newLine();
            
            alignLeft();
            addText("-".repeat(32)); newLine();
            
            addText(formatLine("Nota:", "{{ $order->order_code }}")); newLine();
            addText(formatLine("Tgl :", "{{ $order->created_at->format('d/m/Y H:i') }}")); newLine();
            addText(formatLine("Meja:", "No. {{ $order->table->table_number ?? '-' }}")); newLine();
            addText(formatLine("Nama:", "{{ $order->customer_name }}")); newLine();
            
            addText("-".repeat(32)); newLine();
            
            @foreach($order->items as $item)
                addText("{{ $item->menu->name }}"); newLine();
                addText(formatLine("  {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}", "{{ number_format($item->price * $item->quantity, 0, ',', '.') }}")); newLine();
            @endforeach
            
            addText("-".repeat(32)); newLine();
            
            addText(formatLine("Subtotal:", "{{ number_format($order->subtotal, 0, ',', '.') }}")); newLine();
            @if($order->discount_amount > 0)
                addText(formatLine("Diskon:", "-{{ number_format($order->discount_amount, 0, ',', '.') }}")); newLine();
            @endif
            boldOn();
            addText(formatLine("TOTAL BAYAR:", "{{ number_format($order->total_price, 0, ',', '.') }}")); newLine();
            boldOff();
            
            @if($order->payment)
            addText("-".repeat(32)); newLine();
            addText(formatLine("Metode:", "{{ $order->payment->payment_method }}")); newLine();
            addText(formatLine("Dibayar:", "{{ number_format($order->payment->amount_paid, 0, ',', '.') }}")); newLine();
            addText(formatLine("Kembali:", "{{ number_format($order->payment->amount_return, 0, ',', '.') }}")); newLine();
            @endif
            
            addText("-".repeat(32)); newLine();
            alignCenter();
            addText("*** TERIMA KASIH ***"); newLine();
            addText("Selamat Menikmati Hidangan Anda"); newLine();
            
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
                        '000018f0-0000-1000-8000-00805f9b34fb', // Standard Serial Port Service
                        'e7810a71-73ae-499d-8c15-faa9aef0c3f2',
                        '0000fee7-0000-1000-8000-00805f9b34fb', // Common chinese printer service
                        '49535343-fe7d-4ae5-8fa9-9fafd205e455', // ISSC
                        '0000ff00-0000-1000-8000-00805f9b34fb' 
                    ]
                });

                console.log('Menghubungkan ke perangkat GATT...');
                const server = await device.gatt.connect();

                console.log('Mendapatkan Layanan (Services)...');
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
                const CHUNK_SIZE = 100; // Kirim max 100 byte per sesi untuk mencegah buffer overflow di printer BLE
                
                for (let i = 0; i < data.length; i += CHUNK_SIZE) {
                    const chunk = data.slice(i, i + CHUNK_SIZE);
                    await printCharacteristic.writeValue(chunk);
                }
                
                alert('Berhasil mengirim data cetak ke printer!');
            } catch (error) {
                console.error('Error saat mencetak:', error);
                alert('Gagal mencetak: ' + error.message);
            }
        }
    </script>
</body>
</html>
