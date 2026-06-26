@extends('layouts.cashier')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center">
            <span class="bg-blue-100 text-blue-600 p-2 rounded-xl mr-3 shadow-inner"><i class="fa-solid fa-file-invoice-dollar"></i></span>
            Pesanan Belum Dibayar
        </h2>
        <p class="text-sm text-slate-500 mt-1">Daftar pesanan baru yang menunggu konfirmasi pembayaran.</p>
    </div>
    <span class="bg-blue-100 text-blue-700 font-bold px-4 py-2 rounded-full shadow-sm text-sm" id="count-menunggu">{{ $orders->count() }} Pesanan</span>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($orders as $order)
        <div class="glass-panel rounded-2xl p-5 border-t-4 border-t-blue-500 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="bg-slate-100 text-slate-600 text-xs font-mono font-bold px-2.5 py-1 rounded-md border border-slate-200">{{ $order->order_code }}</span>
                            <span class="text-xs text-slate-400 font-medium bg-slate-50 px-2.5 py-1 rounded-full"><i class="fa-regular fa-clock mr-1"></i>{{ $order->created_at->format('H:i') }}</span>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg flex items-center">
                            @if($order->order_type === 'Take Away')
                                <span class="text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md text-sm mr-2"><i class="fa-solid fa-bag-shopping mr-1"></i> Take Away</span>
                            @else
                                <span class="text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md text-sm mr-2"><i class="fa-solid fa-utensils mr-1"></i> Dine-In</span>
                                Meja {{ $order->table->table_number ?? '-' }}
                            @endif
                        </h3>
                        <p class="text-sm font-medium text-slate-500 mt-2"><i class="fa-regular fa-user mr-1"></i> {{ $order->customer_name }}</p>
                    </div>
                </div>
                
                <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-600 space-y-2 mb-5 border border-slate-100">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-start">
                            <span class="font-medium text-slate-700"><span class="text-indigo-600 font-bold mr-1">{{ $item->quantity }}x</span> {{ $item->menu->name }}</span>
                            <span class="text-slate-400 font-mono whitespace-nowrap ml-2">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                        @if($item->notes) 
                            <p class="text-xs text-blue-600 italic bg-blue-50 px-2 py-1.5 rounded-lg mt-1 border border-blue-100 border-dashed">
                                <i class="fa-solid fa-caret-right mr-1"></i> {{ $item->notes }}
                            </p> 
                        @endif
                    @endforeach
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-4">
                    <p class="text-sm text-slate-500 font-medium">Total Tagihan</p>
                    <span class="text-lg font-black text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                
                <button type="button" onclick="openPaymentModal('{{ route('kasir.updateStatus', $order->id) }}', '{{ $order->order_code }}', {{ $order->total_price }})" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm py-3 rounded-xl transition shadow-md shadow-emerald-500/20 flex justify-center items-center mb-3">
                    <i class="fa-solid fa-check-double mr-2"></i> Terima Pembayaran
                </button>
                <div class="flex space-x-3">
                    <button type="button" onclick="openCancelModal('{{ route('kasir.updateStatus', $order->id) }}', '{{ $order->order_code }}')" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold text-sm py-2.5 rounded-xl border border-red-200 transition shadow-sm">
                        <i class="fa-solid fa-xmark mr-1"></i> Batal
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full flex flex-col items-center justify-center py-20 text-slate-400 opacity-60">
            <i class="fa-solid fa-mug-hot text-6xl mb-4"></i>
            <p class="text-lg font-medium">Belum ada pesanan masuk...</p>
        </div>
    @endforelse
</div>

<!-- Modal Pembayaran -->
<div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closePaymentModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="paymentForm" method="POST" action="">
                @csrf
                <input type="hidden" name="status" value="Diproses">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-money-bill-wave text-emerald-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">
                                Pembayaran Pesanan <span id="payment_order_code" class="text-blue-600"></span>
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Total Tagihan</label>
                                    <div class="text-2xl font-black text-emerald-600" id="display_total_price">Rp 0</div>
                                    <input type="hidden" id="hidden_total_price" value="0">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Metode Pembayaran</label>
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

                                <div id="amount_paid_container">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Uang Diterima (Rp)</label>
                                    <input type="number" name="amount_paid" id="amount_paid" class="w-full border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5" placeholder="Masukkan nominal" onkeyup="calculateChange()">
                                </div>

                                <div id="change_container" class="hidden">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Kembalian</label>
                                    <div class="text-xl font-bold text-orange-500" id="display_change">Rp 0</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Selesai & Proses
                    </button>
                    <button type="button" onclick="closePaymentModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    /* Styling for checked state of custom radio buttons */
    input[type="radio"]:checked + span + span {
        border-color: #3b82f6; /* blue-500 */
    }
    input[type="radio"]:checked + span {
        color: #1d4ed8;
    }
</style>
<script>
    let isModalOpen = false;
    let timeLeft = 10;
    
    setInterval(() => {
        if(!isModalOpen) {
            timeLeft--;
            if (timeLeft <= 0) {
                window.location.reload();
            }
        }
    }, 1000);

    function openPaymentModal(url, code, total) {
        isModalOpen = true;
        document.getElementById('paymentForm').action = url;
        document.getElementById('payment_order_code').innerText = code;
        document.getElementById('hidden_total_price').value = total;
        document.getElementById('display_total_price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        
        // Reset form
        document.querySelector('input[name="payment_method"][value="Tunai"]').checked = true;
        document.getElementById('amount_paid').value = '';
        document.getElementById('display_change').innerText = 'Rp 0';
        togglePaymentMethod();
        
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        isModalOpen = false;
        timeLeft = 10; // Reset timer when modal closed
    }

    function togglePaymentMethod() {
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        const amountContainer = document.getElementById('amount_paid_container');
        const changeContainer = document.getElementById('change_container');
        const amountInput = document.getElementById('amount_paid');

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
        const total = parseInt(document.getElementById('hidden_total_price').value) || 0;
        const paid = parseInt(document.getElementById('amount_paid').value) || 0;
        const changeContainer = document.getElementById('change_container');
        const displayChange = document.getElementById('display_change');

        if (paid > 0 && paid >= total) {
            changeContainer.classList.remove('hidden');
            displayChange.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(paid - total);
        } else {
            changeContainer.classList.add('hidden');
        }
    }
</script>
@endsection

