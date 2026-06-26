@extends('layouts.cashier')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center">
            <span class="bg-blue-100 text-blue-600 p-2 rounded-xl mr-3 shadow-inner"><i class="fa-solid fa-bell-concierge"></i></span>
            Siap Diantar
        </h2>
        <p class="text-sm text-slate-500 mt-1">Pesanan yang sudah selesai dimasak dan menunggu disajikan.</p>
    </div>
    <span class="bg-blue-100 text-blue-700 font-bold px-4 py-2 rounded-full shadow-sm text-sm">{{ $orders->count() }} Pesanan</span>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($orders as $order)
        <div class="glass-panel rounded-2xl p-5 border-t-4 border-t-blue-500 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="bg-slate-100 text-slate-600 text-xs font-mono font-bold px-2.5 py-1 rounded-md border border-slate-200">{{ $order->order_code }}</span>
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
            </div>

            <div class="mt-6">
                <div class="flex space-x-3 w-full">
                    <form action="{{ route('kasir.updateStatus', $order->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="status" value="Selesai">
                        <button type="submit" onclick="return confirm('Tandai pesanan telah selesai diantar/diambil?')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3 rounded-xl transition shadow-md shadow-blue-500/20 flex justify-center items-center">
                            <i class="fa-solid fa-clipboard-check mr-2"></i> Pesanan Selesai
                        </button>
                    </form>
                    <a href="{{ route('kasir.printReceipt', $order->id) }}" target="_blank" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 px-4 py-3 rounded-xl text-sm flex items-center justify-center font-bold transition shadow-sm" title="Cetak Struk Pelanggan">
                        <i class="fa-solid fa-receipt"></i>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full flex flex-col items-center justify-center py-20 text-slate-400 opacity-60">
            <i class="fa-solid fa-check-double text-6xl mb-4"></i>
            <p class="text-lg font-medium">Semua pesanan sudah diantar...</p>
        </div>
    @endforelse
</div>
@endsection

