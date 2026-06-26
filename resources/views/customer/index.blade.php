<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Kafe - Meja {{ $currentTable }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .menu-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .menu-card:active {
            transform: scale(0.98);
        }
        
        .glass-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        .glass-bottom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 -10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="text-slate-800 pb-28 overflow-x-hidden">

    <!-- Header Area -->
    <header class="glass-header sticky top-0 z-40 border-b border-slate-200/60 shadow-sm px-5 py-3">
        <div class="max-w-md mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md shadow-blue-500/30 bg-white overflow-hidden border border-slate-100">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain p-1">
                </div>
                <div>
                    <h1 class="font-extrabold text-lg text-slate-900 leading-tight tracking-tight">Terrralog coffre n eatery</h1>
                    <p class="text-[11px] font-semibold text-blue-600 tracking-wide uppercase">Pesan Langsung</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-2">
                <a href="{{ route('customer.history') }}" class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm border border-blue-100 hover:bg-blue-100 transition" title="Riwayat Pesanan">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </a>
                <div class="bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-xl text-center">
                    <span class="block text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-0.5">Meja</span>
                    <span class="block text-sm font-black text-slate-800 leading-none">{{ $currentTable }}</span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-md mx-auto p-4">
        
        <!-- Welcome text -->
        <div class="mb-5 mt-2 px-1">
            <h2 class="text-2xl font-black text-slate-800 leading-tight">Halo! 👋<br><span class="text-slate-500 font-medium text-lg">Mau makan apa hari ini?</span></h2>
        </div>

        <!-- Search Bar -->
        <div class="mb-6 relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
            </div>
            <input type="text" id="searchInput" oninput="filterMenus()" placeholder="Cari makanan, minuman..." class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-white shadow-sm focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all font-medium text-sm text-slate-800 placeholder-slate-400">
        </div>

        <!-- Categories -->
        <div class="flex space-x-3 overflow-x-auto pb-2 mb-6 no-scrollbar px-1">
            <button onclick="filterCategory('all')" class="category-btn bg-blue-600 text-white border-transparent px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap shadow-md shadow-blue-600/20 transition-all shrink-0" id="btn-all">
                Semua
            </button>
            @foreach($categories as $cat)
                <button onclick="filterCategory('{{ $cat->id }}')" class="category-btn bg-white text-slate-600 border border-slate-200 px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap shadow-sm hover:border-blue-300 transition-all shrink-0" id="btn-{{ $cat->id }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <!-- Menu Grid -->
        <div class="grid grid-cols-1 gap-4" id="menuContainer">
            @foreach($menus as $menu)
                @php 
                    $isAvailable = $menu->stock && $menu->stock->current_stock > 0; 
                @endphp
                <div class="menu-card bg-white p-3 rounded-2xl shadow-sm border border-slate-100 flex items-stretch space-x-4 {{ $isAvailable ? '' : 'opacity-60 grayscale-[20%]' }}" data-category="{{ $menu->category_id }}" data-name="{{ strtolower($menu->name) }}">
                    
                    <!-- Image -->
                    <div class="w-28 h-28 rounded-xl bg-slate-50 flex-shrink-0 overflow-hidden relative">
                        @if($menu->image)
                            <img src="{{ asset('storage/' . $menu->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300 text-3xl bg-slate-100">
                                <i class="fa-solid fa-utensils"></i>
                            </div>
                        @endif
                        
                        @if(!$isAvailable)
                            <div class="absolute inset-0 bg-white/50 backdrop-blur-[1px] flex items-center justify-center">
                                <span class="bg-red-500 text-white font-bold px-3 py-1 rounded-lg text-[10px] shadow-sm tracking-wide">HABIS</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 flex flex-col justify-between py-1 pr-1">
                        <div>
                            <h3 class="font-extrabold text-slate-800 text-base leading-tight mb-1">{{ $menu->name }}</h3>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-snug font-medium">{{ $menu->description ?? 'Pilihan hidangan spesial untuk Anda.' }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-blue-600 font-black text-[15px]">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            @if($isAvailable)
                                <button onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, {{ $menu->stock->current_stock }})" class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-full transition-colors font-bold shadow-sm">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            @else
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">KOSONG</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Floating Cart Bar -->
    <div id="floatingCart" class="fixed bottom-0 left-0 right-0 glass-bottom border-t border-slate-200 z-50 hidden transition-all duration-300 pb-safe">
        <div class="max-w-md mx-auto px-5 py-4 flex justify-between items-center">
            
            <button onclick="toggleCartModal(true)" class="flex items-center space-x-3 text-left">
                <div class="relative">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-xl">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <span id="cartCount" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">0</span>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Total Pesanan</p>
                    <p class="text-lg font-black text-slate-800 leading-tight" id="cartTotalDisplay">Rp 0</p>
                </div>
            </button>
            
            <button onclick="openCheckoutModal()" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-extrabold text-sm py-3.5 px-6 rounded-2xl shadow-lg shadow-blue-600/30 transition-all flex items-center space-x-2">
                <span>Checkout</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
            
        </div>
    </div>

    <!-- Modal Cart Details -->
    <div id="cartModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] hidden flex-col justify-end transition-opacity">
        <div class="bg-white w-full max-w-md mx-auto rounded-t-3xl flex flex-col max-h-[85vh] shadow-2xl">
            
            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center sticky top-0 bg-white rounded-t-3xl z-10">
                <h3 class="font-extrabold text-lg text-slate-800">Keranjang Saya</h3>
                <button onclick="toggleCartModal(false)" class="w-8 h-8 flex items-center justify-center bg-slate-100 text-slate-500 rounded-full hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <!-- Cart Items -->
            <div id="cartItemsList" class="p-6 space-y-5 overflow-y-auto custom-scrollbar">
                <!-- Items injected via JS -->
            </div>
            
            <!-- Modal Footer -->
            <div class="p-6 border-t border-slate-100 bg-slate-50 mt-auto">
                <button onclick="toggleCartModal(false)" class="w-full bg-white border border-slate-200 text-slate-700 font-bold py-3.5 rounded-xl text-sm shadow-sm hover:bg-slate-50 transition-colors">
                    Lanjut Pilih Menu
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Checkout -->
    <div id="checkoutModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[70] hidden flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl">
            
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 flex items-center justify-center rounded-xl">
                    <i class="fa-solid fa-receipt text-lg"></i>
                </div>
                <h3 class="font-extrabold text-xl text-slate-800">Checkout</h3>
            </div>
            
            <form action="{{ route('customer.checkout') }}" method="POST">
                @csrf
                <input type="hidden" name="cart_data" id="cartDataInput">

                <!-- Nama Pemesan -->
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Nama Pemesan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-regular fa-user text-slate-400"></i>
                        </div>
                        <input type="text" name="customer_name" required placeholder="Contoh: Budi" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-50 focus:border-blue-400 outline-none text-sm font-semibold transition-all">
                    </div>
                </div>



                <!-- Summary -->
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 mb-6">
                    <div class="flex justify-between text-sm text-slate-500 font-semibold mb-2">
                        <span>Subtotal Harga</span>
                        <span id="summarySubtotal">Rp 0</span>
                    </div>
                    <div class="border-t border-slate-200 border-dashed pt-3 flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Pembayaran</span>
                        <span id="summaryTotal" class="text-xl font-black text-blue-600">Rp 0</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col space-y-3">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-extrabold py-3.5 rounded-xl text-sm shadow-lg shadow-blue-600/20 transition-all flex justify-center items-center">
                        <i class="fa-solid fa-fire-burner mr-2"></i> Kirim Pesanan ke Dapur
                    </button>
                    <button type="button" onclick="closeCheckoutModal()" class="w-full bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-bold py-3.5 rounded-xl text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS Logic -->
    <script>
        let cart = [];
        let activeCategory = 'all';

        // PWA Safe Area Padding for mobile browsers
        document.documentElement.style.setProperty('--sat', 'env(safe-area-inset-top)');
        document.documentElement.style.setProperty('--sab', 'env(safe-area-inset-bottom)');

        // Filtering
        function filterMenus() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const items = document.querySelectorAll('.menu-card');

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
                btn.classList.remove('bg-blue-600', 'text-white', 'border-transparent', 'shadow-md');
                btn.classList.add('bg-white', 'text-slate-600', 'border-slate-200', 'shadow-sm');
            });

            const activeBtn = document.getElementById('btn-' + catId);
            activeBtn.classList.remove('bg-white', 'text-slate-600', 'border-slate-200', 'shadow-sm');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'border-transparent', 'shadow-md');

            filterMenus();
        }

        // Cart Management
        function addToCart(id, name, price, maxStock) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                if (existingItem.quantity < maxStock) {
                    existingItem.quantity++;
                } else {
                    alert('Batas stok maksimal untuk menu ini tercapai.');
                    return;
                }
            } else {
                cart.push({ id, name, price, quantity: 1, notes: '', maxStock });
            }
            
            // Provide visual feedback (Optional vibration on mobile)
            if (navigator.vibrate) navigator.vibrate(50);
            
            updateCartDisplay();
        }

        function changeQuantity(id, amount) {
            const item = cart.find(i => i.id === id);
            if (item) {
                item.quantity += amount;
                if (item.quantity > item.maxStock) {
                    alert('Maaf, stok tidak mencukupi.');
                    item.quantity = item.maxStock;
                }
                if (item.quantity <= 0) {
                    cart = cart.filter(i => i.id !== id);
                }
            }
            updateCartDisplay();
            renderCartItems();
        }

        function updateNotes(id, text) {
            const item = cart.find(i => i.id === id);
            if (item) item.notes = text;
        }

        function updateCartDisplay() {
            const floatingCart = document.getElementById('floatingCart');
            const cartCount = document.getElementById('cartCount');
            const cartTotalDisplay = document.getElementById('cartTotalDisplay');

            let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            let totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            if (totalItems > 0) {
                floatingCart.classList.remove('hidden');
                cartCount.innerText = totalItems;
                
                // Add bounce animation
                cartCount.classList.add('animate-bounce');
                setTimeout(() => cartCount.classList.remove('animate-bounce'), 500);

                cartTotalDisplay.innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');
            } else {
                floatingCart.classList.add('hidden');
                toggleCartModal(false);
            }
        }

        function renderCartItems() {
            const list = document.getElementById('cartItemsList');
            list.innerHTML = '';

            cart.forEach(item => {
                list.innerHTML += `
                    <div class="relative bg-white border border-slate-100 rounded-2xl p-4 shadow-sm group">
                        
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1 pr-3">
                                <h4 class="font-extrabold text-sm text-slate-800 leading-tight mb-1">${item.name}</h4>
                                <p class="text-[13px] text-blue-600 font-bold">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</p>
                            </div>
                            
                            <!-- Quantity Controls -->
                            <div class="flex items-center space-x-1 bg-slate-50 border border-slate-200 p-1 rounded-xl">
                                <button type="button" onclick="changeQuantity(${item.id}, -1)" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg text-slate-600 shadow-sm font-bold active:bg-slate-100"><i class="fa-solid fa-minus text-[10px]"></i></button>
                                <span class="w-6 text-center text-xs font-black text-slate-800">${item.quantity}</span>
                                <button type="button" onclick="changeQuantity(${item.id}, 1)" class="w-7 h-7 flex items-center justify-center bg-blue-600 rounded-lg text-white shadow-sm font-bold active:bg-blue-700"><i class="fa-solid fa-plus text-[10px]"></i></button>
                            </div>
                        </div>
                        
                        <!-- Notes Input -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <i class="fa-regular fa-comment-dots text-slate-400 text-[10px]"></i>
                            </div>
                            <input type="text" value="${item.notes}" onchange="updateNotes(${item.id}, this.value)" placeholder="Catatan (opsional)..." class="w-full pl-7 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-100 focus:bg-white transition-all text-slate-700 placeholder-slate-400">
                        </div>
                    </div>
                `;
            });
        }

        // Modals Management
        function toggleCartModal(show) {
            const modal = document.getElementById('cartModal');
            if (show) {
                renderCartItems();
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        function openCheckoutModal() {
            toggleCartModal(false);
            document.getElementById('cartDataInput').value = JSON.stringify(cart);
            recalculateCheckoutSummary();
            const modal = document.getElementById('checkoutModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCheckoutModal() {
            document.getElementById('checkoutModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function recalculateCheckoutSummary() {
            let subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            let total = Math.max(0, subtotal);

            document.getElementById('summarySubtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('summaryTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
        }
    </script>
</body>
</html>
