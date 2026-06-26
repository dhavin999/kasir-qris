@extends('layouts.customer')

@section('title', 'Meja Terisi - Kasir QRIS')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden p-8 text-center">
        
        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-2">Meja Sedang Ditempati</h2>
        <p class="text-gray-600 mb-8">Meja <strong>{{ $tableModel->table_number }}</strong> saat ini tercatat sedang digunakan. Jika Anda adalah pelanggan baru di meja ini, silakan lakukan request agar meja dibuka.</p>

        @if(session('success_request') || $tableModel->is_unlock_requested)
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            {{ session('success_request') ?? 'Request kesediaan meja telah dikirim. Silakan tunggu konfirmasi.' }}
                        </p>
                    </div>
                </div>
            </div>
            <button onclick="window.location.reload()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200">
                Refresh Halaman
            </button>
        @else
            <div class="space-y-4">
                <form action="{{ route('customer.requestUnlock', $tableModel->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-xl transition duration-200 flex justify-center items-center gap-2">
                        <span>Request Kesediaan Meja</span>
                    </button>
                </form>
                
                <a href="#" onclick="alert('Silakan tunggu pelayan untuk mengosongkan meja terlebih dahulu.');" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-xl transition duration-200">
                    Batal
                </a>
            </div>
        @endif

    </div>
</div>
@endsection

