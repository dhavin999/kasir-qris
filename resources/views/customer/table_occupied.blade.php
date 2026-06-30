<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meja Terisi - Kasir QRIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden p-8 text-center border border-slate-100">
            
            <div class="w-24 h-24 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-extrabold text-slate-800 mb-3 tracking-tight">Meja Sedang Ditempati</h2>
            <p class="text-slate-500 mb-8 text-sm leading-relaxed">Meja <strong class="text-slate-800 font-black text-base">{{ $tableModel->table_number }}</strong> saat ini tercatat sedang digunakan. Jika meja ini sudah kosong dan Anda pelanggan baru, silakan minta pelayan untuk mengosongkan meja di sistem.</p>

            <button onclick="window.location.reload()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-2xl transition shadow-lg shadow-blue-600/30 mb-3">
                Cek Ulang Status Meja
            </button>
            <a href="#" onclick="alert('Silakan tunggu pelayan untuk mengosongkan meja terlebih dahulu.');" class="block w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-4 px-4 rounded-2xl transition">
                Kembali
            </a>

        </div>
    </div>
</body>
</html>
