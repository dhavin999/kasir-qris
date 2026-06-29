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
            
            addText(formatLine("Laporan:", "TUTUP KASIR (Z-REPORT)")); newLine();
            addText(formatLine("Tanggal:", "{{ now()->format('d M Y') }}")); newLine();
            addText(formatLine("Cetak  :", "{{ now()->format('H:i:s') }}")); newLine();
            addText(formatLine("Kasir  :", "{{ Auth::user()->name }}")); newLine();
            
            addText("-".repeat(32)); newLine();
            boldOn(); addText("RINGKASAN PESANAN"); boldOff(); newLine();
            addText("-".repeat(32)); newLine();
            
            addText(formatLine("Dine In", "{{ $dineInCount }} Nota")); newLine();
            addText(formatLine("Take Away", "{{ $takeAwayCount }} Nota")); newLine();
            addText(formatLine("Total Nota Hari Ini", "{{ $totalOrders }} Nota")); newLine();

            addText("-".repeat(32)); newLine();
            boldOn(); addText("RINGKASAN KEUANGAN"); boldOff(); newLine();
            addText("-".repeat(32)); newLine();

            addText(formatLine("Total Penjualan", "{{ number_format($totalSubtotal, 0, ',', '.') }}")); newLine();

            addText("-".repeat(32)); newLine();
            boldOn(); addText("METODE PEMBAYARAN"); boldOff(); newLine();
            addText("-".repeat(32)); newLine();

            addText(formatLine("QRIS", "{{ number_format($totalQRIS, 0, ',', '.') }}")); newLine();
            addText(formatLine("Tunai", "{{ number_format($totalTunai, 0, ',', '.') }}")); newLine();
            addText(formatLine("Diterima(Tunai)", "{{ number_format($totalAmountPaidTunai, 0, ',', '.') }}")); newLine();
            addText(formatLine("Kembali(Tunai)", "-{{ number_format($totalAmountReturnTunai, 0, ',', '.') }}")); newLine();

            addText("-".repeat(32)); newLine();
            boldOn();
            addText("TOTAL PENDAPATAN:"); newLine();
            alignRight();
            addText("Rp {{ number_format($totalRevenue, 0, ',', '.') }}"); newLine();
            alignLeft();
            addText("TOTAL UANG TUNAI DI LACI:"); newLine();
            alignRight();
            addText("Rp {{ number_format($totalTunai, 0, ',', '.') }}"); newLine();
            boldOff();

            alignCenter();
            addText("-".repeat(32)); newLine();
            addText("Saya menyatakan bahwa laporan ini"); newLine();
            addText("dicetak sesuai dengan keadaan"); newLine();
            addText("sistem dan total uang masuk."); newLine();
            newLine(); newLine(); newLine();
            addText("( {{ Auth::user()->name }} )"); newLine();
            addText("Tanda Tangan Kasir"); newLine();
            
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
                
                alert('Berhasil mengirim data laporan ke printer!');
            } catch (error) {
                console.error('Error saat mencetak:', error);
                alert('Gagal mencetak: ' + error.message);
            }
        }
    </script>
</body>
</html>

