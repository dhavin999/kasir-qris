@extends('layouts.cashier')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center">
            <span class="bg-indigo-100 text-indigo-600 p-2 rounded-xl mr-3 shadow-inner"><i class="fa-solid fa-chair"></i></span>
            Kelola Status Meja
        </h2>
        <p class="text-sm text-slate-500 mt-1">Ubah status meja menjadi terisi atau kosong, serta atur permintaan buka meja dari pelanggan.</p>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
    @forelse($tables as $table)
        <div class="glass-panel rounded-2xl p-4 flex flex-col items-center text-center transition-all duration-300 {{ $table->status == 'Kosong' ? 'border-2 border-emerald-400/50 hover:border-emerald-400 bg-emerald-50/30' : 'border-2 border-slate-200/50 bg-slate-50/50' }}">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mb-3 {{ $table->status == 'Kosong' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-500' }}">
                <i class="fa-solid fa-chair text-2xl"></i>
            </div>
            <h3 class="font-extrabold text-slate-800 text-lg">Meja {{ $table->table_number }}</h3>
            
            <div class="mt-2 mb-4">
                @if($table->is_unlock_requested)
                    <span class="inline-block bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold animate-pulse">Request Buka!</span>
                @else
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $table->status == 'Kosong' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                        {{ $table->status }}
                    </span>
                @endif
            </div>

            <form action="{{ route('kasir.tables.toggle', $table->id) }}" method="POST" class="w-full mt-auto">
                @csrf
                @if($table->status == 'Kosong')
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 rounded-xl text-xs transition shadow-md">
                        <i class="fa-solid fa-lock mr-1"></i> Tandai Terisi
                    </button>
                @else
                    <button type="submit" onclick="return confirm('Kosongkan Meja {{ $table->table_number }}?')" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 rounded-xl text-xs transition shadow-md {{ $table->is_unlock_requested ? 'ring-2 ring-red-400 ring-offset-1' : '' }}">
                        <i class="fa-solid fa-unlock mr-1"></i> Buka Meja
                    </button>
                @endif
            </form>
        </div>
    @empty
        <div class="col-span-full flex flex-col items-center justify-center py-20 text-slate-400 opacity-60">
            <i class="fa-solid fa-chair text-6xl mb-4"></i>
            <p class="text-lg font-medium">Belum ada meja yang terdaftar.</p>
        </div>
    @endforelse
</div>
@endsection

