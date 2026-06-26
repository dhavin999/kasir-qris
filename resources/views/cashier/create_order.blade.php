<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Kasir - Take Away</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #94a3b8; }
        
        .menu-item {
            transition: all 0.2s ease-in-out;
        }
        .menu-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        /* Glass effect for Cart Sidebar */
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
        }
        
        /* Styling for checked state of custom radio buttons */
        input[type="radio"]:checked + span + span {
            border-color: #3b82f6; /* blue-500 */
        }
        input[type="radio"]:checked + span {
            color: #1d4ed8;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 h-screen overflow-hidden flex flex-col">

    <!-- Top Navigation -->
    <nav class="bg-white px-6 py-3 border-b border-slate-200 flex justify-between items-center z-40 shadow-sm relative">
        <div class="flex items-center space-x-4">
            <a href="{{ route('kasir.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="font-extrabold text-xl text-slate-800 tracking-tight">POS TAKE AWAY</h1>
                <p class="text-xs text-slate-500 font-medium">Buat pesanan langsung di kasir</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <div class="bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-lg flex items-center space-x-2 text-sm text-blue-700 font-bold">
                <i class="fa-solid fa-user-circle"></i>
                <span>{{ Auth::user()->name ?? 'Kasir Aktif' }}</span>
            </div>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden">
        
        <!-- Main Content Area: Menu Selection -->
        <div class="flex-1 flex flex-col bg-slate-50 relative z-0">
            <!-- Filter Bar -->
            <div class="p-4 bg-white border-b border-slate-200 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 sticky top-0 z-10 shadow-sm">
                <!-- Search Input -->
                <div class="relative sm:w-1/3">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                    </div>
                    <input type="text" id="searchInput" oninput="filterMenus()" placeholder="Cari menu..." class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border border-transparent rounded-xl focus:bg-white focus:border-blue-300 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium">
                </div>
                
                <!-- Categories Carousel -->
                <div class="flex-1 flex space-x-2 overflow-x-auto scrollbar-none items-center px-1">
                    <button onclick="filterCategory('all')" class="category-btn bg-blue-600 text-white border-transparent px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap shadow-md shadow-blue-600/20 transition-all" id="btn-all">
                        Semua Menu
                    </button>
                    @foreach($categories as $cat)
                        <button onclick="filterCategory('{{ $cat->id }}')" class="category-btn bg-white text-slate-600 border border-slate-200 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all" id="btn-{{ $cat->id }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Menu Grid -->
            <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="menuContainer">
                    @foreach($menus as $menu)
                        @php 
                            $isAvailable = $menu->stock && $menu->stock->current_stock > 0; 
                        @endphp
                        <div class="menu-item bg-white rounded-2xl border border-slate-100 overflow-hidden flex flex-col {{ $isAvailable ? 'cursor-pointer' : 'opacity-60 grayscale-[30%]' }}" 
                             data-category="{{ $menu->category_id }}" data-name="{{ strtolower($menu->name) }}"
                             onclick="{{ $isAvailable ? 'addToCart('.$menu->id.', \''.addslashes($menu->name).'\', '.$menu->price.', '.$menu->stock->current_stock.')' : '' }}">
                            
                            <!-- Image Container -->
                            <div class="h-36 bg-slate-100 w-full relative group">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 text-5xl">
                                        <i class="fa-solid fa-utensils"></i>
                                    </div>
                                @endif
                                
                                <!-- Stock overlay overlay -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10">
                                    @if($isAvailable)
                                        <span class="bg-blue-600 text-white font-bold px-4 py-2 rounded-xl shadow-lg transform scale-90 group-hover:scale-100 transition-transform">
                                            <i class="fa-solid fa-plus mr-1"></i> Tambah
                                        </span>
                                    @endif
                                </div>

                                @if(!$isAvailable)
                                    <div class="absolute inset-0 bg-white/70 backdrop-blur-[2px] flex items-center justify-center z-20">
                                        <span class="bg-red-500 text-white font-bold px-4 py-1.5 rounded-full text-xs shadow-md border border-red-600">HABIS</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-3 flex-1 flex flex-col relative z-20 bg-white">
                                <h3 class="font-bold text-slate-800 text-sm menu-title leading-snug mb-1 line-clamp-2">{{ $menu->name }}</h3>
                                <div class="mt-auto flex justify-between items-end pt-2">
                                    <span class="text-blue-600 font-black text-sm">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                    @if($isAvailable)
                                        <span class="text-[10px] bg-slate-100 border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md font-bold">Stok: {{ $menu->stock->current_stock }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Right: Cart & Checkout -->
        <div class="w-[360px] lg:w-[400px] glass-sidebar flex flex-col border-l border-slate-200 shadow-2xl relative z-20">
            
            <!-- Cart Header -->
            <div class="p-5 border-b border-slate-200 bg-white flex justify-between items-center">
                <h2 class="font-extrabold text-slate-800 text-lg flex items-center">
                    <span class="bg-blue-100 text-blue-600 p-2 rounded-xl mr-3"><i class="fa-solid fa-cart-shopping"></i></span>
                    Pesanan Saat Ini
                </h2>
                <span id="cartCountBadge" class="bg-slate-800 text-white text-xs font-bold px-2.5 py-1 rounded-full hidden">0</span>
            </div>
            
            <!-- Cart Items List -->
            <div class="flex-1 min-h-0 overflow-y-auto p-4 space-y-3 custom-scrollbar bg-slate-50/50" id="cartItemsList">
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center h-full text-slate-400 opacity-60">
                    <i class="fa-solid fa-basket-shopping text-5xl mb-4 text-slate-300"></i>
                    <p class="text-sm font-medium">Belum ada menu yang dipilih</p>
                </div>
            </div>

            <!-- Checkout Form Area -->
            <div class="p-5 bg-white border-t border-slate-200 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.05)] overflow-y-auto max-h-[60vh] shrink-0 custom-scrollbar">
                <form action="{{ route('kasir.order.store') }}" method="POST" id="checkoutForm">
                    @csrf
                    <input type="hidden" name="cart_data" id="cartDataInput">

                    <div class="mb-4">
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5 tracking-wide uppercase">Nama Pemesan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-user text-slate-400"></i>
                            </div>
                            <input type="text" name="customer_name" id="customerName" required placeholder="Masukkan nama panggilan..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-100 outline-none text-sm font-semibold text-slate-800 transition-all">
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-4">
                        <div class="flex justify-between text-xs text-slate-500 font-bold mb-1">
                            <span>Subtotal Items:</span>
                            <span id="summaryTotal">Rp 0</span>
                        </div>
                        <div class="border-t border-slate-200 border-dashed my-2"></div>
                        <div class="flex justify-between items-end">
                            <span class="text-sm font-extrabold text-slate-800">Total Tagihan:</span>
                            <span id="summaryFinal" class="text-xl font-black text-blue-600">Rp 0</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5 tracking-wide uppercase">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm hover:bg-slate-50 focus-within:ring-2 focus-within:ring-blue-500">
                                <input type="radio" name="payment_method" value="Tunai" class="sr-only" checked onchange="togglePaymentMethod()">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium text-slate-900"><i class="fa-solid fa-money-bill text-emerald-500 mr-2"></i> Tunai</span>
                                </span>
                                <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent peer-checked:border-blue-500" aria-hidden="true"></span>
                            </label>
                            <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm hover:bg-slate-50 focus-within:ring-2 focus-within:ring-blue-500">
                                <input type="radio" name="payment_method" value="QRIS" class="sr-only" onchange="togglePaymentMethod()">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium text-slate-900"><i class="fa-solid fa-qrcode text-blue-500 mr-2"></i> QRIS</span>
                                </span>
                                <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent peer-checked:border-blue-500" aria-hidden="true"></span>
                            </label>
                        </div>
                    </div>

                    <div id="amount_paid_container" class="mb-4">
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5 tracking-wide uppercase">Uang Diterima (Rp)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-rupiah-sign text-slate-400"></i>
                            </div>
                            <input type="number" name="amount_paid" id="amountPaid" placeholder="0" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-100 outline-none text-sm font-semibold text-slate-800 transition-all" onkeyup="calculateChange()">
                        </div>
                    </div>

                    <div id="change_container" class="hidden bg-orange-50 p-3 rounded-xl border border-orange-100 mb-4 flex justify-between items-center">
                        <span class="text-xs font-bold text-orange-700">Kembalian:</span>
                        <span id="summaryChange" class="text-sm font-black text-orange-600">Rp 0</span>
                    </div>

                    <button type="button" onclick="submitOrder()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm py-3.5 rounded-xl shadow-lg shadow-blue-600/30 transition-all transform active:scale-[0.98] flex items-center justify-center space-x-2">
                        <span><i class="fa-solid fa-paper-plane mr-1"></i> PROSES PESANAN SEKARANG</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let activeCategory = 'all';

        // Filter Categories and Search
        function filterMenus() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const items = document.querySelectorAll('.menu-item');

            items.forEach(item => {
                const name = item.getAttribute('data-name');
                const category = item.getAttribute('data-category');

                const matchesSearch = name.includes(searchValue);
                const matchesCategory = (activeCategory === 'all' || category === activeCategory);

                if (matchesSearch && matchesCategory) {
                    item.style.setProperty('display', 'flex', 'important');
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });
        }

        function filterCategory(catId) {
            activeCategory = catId;
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
                btn.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
            });

            const activeBtn = document.getElementById('btn-' + catId);
            activeBtn.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-md');

            filterMenus();
        }

        // Cart Logic
        function addToCart(id, name, price, maxStock) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                if (existingItem.quantity < maxStock) {
                    existingItem.quantity++;
                } else {
                    alert('Maaf, kuantitas melebihi batas stok maksimal yang tersedia.');
                    return;
                }
            } else {
                cart.push({ id, name, price, quantity: 1, notes: '', maxStock });
            }
            renderCartItems();
        }

        function changeQuantity(id, amount) {
            const item = cart.find(i => i.id === id);
            if (item) {
                item.quantity += amount;
                if (item.quantity > item.maxStock) {
                    alert('Stok tidak mencukupi.');
                    item.quantity = item.maxStock;
                }
                if (item.quantity <= 0) {
                    cart = cart.filter(i => i.id !== id);
                }
            }
            renderCartItems();
        }

        function updateNotes(id, text) {
            const item = cart.find(i => i.id === id);
            if (item) item.notes = text;
        }

        function renderCartItems() {
            const list = document.getElementById('cartItemsList');
            const summaryTotal = document.getElementById('summaryTotal');
            const summaryFinal = document.getElementById('summaryFinal');
            const badge = document.getElementById('cartCountBadge');
            
            list.innerHTML = '';
            let totalPrice = 0;
            let totalQty = 0;

            if (cart.length === 0) {
                badge.classList.add('hidden');
                list.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-slate-400 opacity-60 mt-10">
                        <i class="fa-solid fa-basket-shopping text-5xl mb-4 text-slate-300"></i>
                        <p class="text-sm font-medium">Belum ada menu yang dipilih</p>
                    </div>`;
            } else {
                cart.forEach(item => {
                    let itemTotal = item.price * item.quantity;
                    totalPrice += itemTotal;
                    totalQty += item.quantity;
                    
                    list.innerHTML += `
                        <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-sm relative group transition-all hover:border-blue-300">
                            <button onclick="changeQuantity(${item.id}, -${item.quantity})" class="absolute -top-2.5 -right-2.5 bg-red-100 hover:bg-red-500 text-red-600 hover:text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold transition-all shadow-sm opacity-0 group-hover:opacity-100 z-10">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1 pr-2">
                                    <h4 class="font-bold text-sm text-slate-800 leading-tight">${item.name}</h4>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Rp ${item.price.toLocaleString('id-ID')} / item</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-blue-600 font-black">Rp ${itemTotal.toLocaleString('id-ID')}</p>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mt-2">
                                <input type="text" value="${item.notes}" onchange="updateNotes(${item.id}, this.value)" placeholder="📝 Catatan khusus..." class="w-3/5 px-2.5 py-1.5 border border-slate-200 rounded-md text-[11px] focus:outline-none focus:border-blue-300 bg-slate-50 placeholder-slate-400 font-medium transition-colors">
                                
                                <div class="flex items-center space-x-1 bg-slate-100 border border-slate-200 p-0.5 rounded-lg">
                                    <button type="button" onclick="changeQuantity(${item.id}, -1)" class="w-6 h-6 bg-white rounded flex items-center justify-center text-slate-600 hover:text-red-600 hover:bg-red-50 shadow-sm transition-colors text-xs font-bold">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <span class="w-6 text-center text-xs font-bold text-slate-800">${item.quantity}</span>
                                    <button type="button" onclick="changeQuantity(${item.id}, 1)" class="w-6 h-6 bg-white rounded flex items-center justify-center text-slate-600 hover:text-blue-600 hover:bg-blue-50 shadow-sm transition-colors text-xs font-bold">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });

                badge.classList.remove('hidden');
                badge.innerText = totalQty;
            }

            let formatTotal = 'Rp ' + totalPrice.toLocaleString('id-ID');
            summaryTotal.innerText = formatTotal;
            summaryFinal.innerText = formatTotal;
        }

        function togglePaymentMethod() {
            const method = document.querySelector('input[name="payment_method"]:checked').value;
            const amountContainer = document.getElementById('amount_paid_container');
            const changeContainer = document.getElementById('change_container');
            const amountInput = document.getElementById('amountPaid');

            if (method === 'Tunai') {
                amountContainer.classList.remove('hidden');
                amountInput.required = true;
                calculateChange();
            } else {
                amountContainer.classList.add('hidden');
                changeContainer.classList.add('hidden');
                amountInput.required = false;
                amountInput.value = '';
            }
        }

        function calculateChange() {
            let totalPrice = 0;
            cart.forEach(item => {
                totalPrice += item.price * item.quantity;
            });

            const paid = parseInt(document.getElementById('amountPaid').value) || 0;
            const changeContainer = document.getElementById('change_container');
            const summaryChange = document.getElementById('summaryChange');

            if (paid > 0 && paid >= totalPrice) {
                changeContainer.classList.remove('hidden');
                summaryChange.innerText = 'Rp ' + (paid - totalPrice).toLocaleString('id-ID');
            } else {
                changeContainer.classList.add('hidden');
            }
        }

        function submitOrder() {
            if (cart.length === 0) {
                alert('Keranjang belanja kosong! Silakan pilih menu terlebih dahulu.');
                return;
            }

            const name = document.getElementById('customerName').value.trim();
            if (!name) {
                alert('Silakan masukkan nama pelanggan terlebih dahulu!');
                document.getElementById('customerName').focus();
                return;
            }

            const method = document.querySelector('input[name="payment_method"]:checked').value;
            if (method === 'Tunai') {
                let totalPrice = 0;
                cart.forEach(item => { totalPrice += item.price * item.quantity; });
                const paid = parseInt(document.getElementById('amountPaid').value) || 0;
                if (paid < totalPrice) {
                    alert('Nominal uang diterima kurang dari total tagihan!');
                    document.getElementById('amountPaid').focus();
                    return;
                }
            }

            if (confirm(`Proses pesanan Take Away atas nama ${name}?`)) {
                document.getElementById('cartDataInput').value = JSON.stringify(cart);
                document.getElementById('checkoutForm').submit();
            }
        }
    </script>
</body>
</html>

