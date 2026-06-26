<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Table;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerOrderController extends Controller
{
    // 1. Tampilan Utama Menu & Keranjang
    public function index(Request $request)
    {
        if ($request->has('meja')) {
            $tableNumber = $request->query('meja');
            $table = Table::where('table_number', $tableNumber)->first();

            if ($table) {
                // Simpan ID meja dan Nomor meja ke session
                session(['customer_table_id' => $table->id, 'customer_table_number' => $table->table_number]);
            } else {
                return abort(404, 'Nomor meja tidak ditemukan.');
            }
        }

        $tableId = session('customer_table_id');
        $currentTable = session('customer_table_number');

        if (!$currentTable) {
            return abort(403, 'Silakan scan QR Code di meja Anda terlebih dahulu untuk memesan.');
        }

        $tableModel = Table::find($tableId);
        
        $hasOrderedHere = false;
        $orderCodes = session('order_history', []);
        if (!empty($orderCodes)) {
            $hasOrderedHere = Order::whereIn('order_code', $orderCodes)
                ->where('table_id', $tableId)
                // We check if the order was created today to ensure it's a recent order
                ->whereDate('created_at', today())
                ->exists();
        }

        if ($tableModel && $tableModel->status === 'Terisi' && !$hasOrderedHere) {
            return view('customer.table_occupied', compact('tableModel'));
        }

        $categories = Category::all();
        // Hanya tampilkan menu yang status is_available-nya true (aktif)
        $menus = Menu::with('stock')->where('is_available', true)->get(); 

        return view('customer.index', compact('currentTable', 'tableId', 'categories', 'menus'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'cart_data' => 'required|string', 
        ]);

        $cart = json_decode($request->cart_data, true);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja Anda masih kosong!');
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            // Validasi stok sebelum memproses lebih lanjut
            foreach ($cart as $index => $item) {
                if (!isset($item['quantity']) || !is_numeric($item['quantity']) || $item['quantity'] < 1) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Kuantitas pesanan tidak valid.');
                }
                $menu = Menu::with('stock')->lockForUpdate()->findOrFail($item['id']);
                
                // Tolak jika menu sedang dinonaktifkan oleh manajer/admin
                if (!$menu->is_available) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Maaf, menu ' . $menu->name . ' sedang tidak tersedia saat ini.');
                }

                if (!$menu->stock || $menu->stock->current_stock < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Maaf, stok ' . $menu->name . ' tidak mencukupi. Sisa stok: ' . ($menu->stock->current_stock ?? 0));
                }

                // Ambil harga dari database untuk mencegah manipulasi dari sisi klien
                $cart[$index]['price'] = $menu->price;
                $subtotal += $menu->price * $item['quantity'];
            }

            $totalPrice = max(0, $subtotal);
            
            $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            // Buat data Order Utama
            $order = Order::create([
                'table_id' => session('customer_table_id'),
                'order_code' => $orderCode,
                'customer_name' => $request->customer_name,
                'subtotal' => $subtotal,
                'total_price' => $totalPrice,
                'status' => 'Menunggu', 
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'notes' => $item['notes'] ?? null,
                ]);

                // Potong stok seketika
                $menu = Menu::with('stock')->find($item['id']);
                $menu->stock->decrement('current_stock', $item['quantity']);

                StockHistory::create([
                    'stock_id'  => $menu->stock->id,
                    'user_id'   => null, // Customer order
                    'type'      => 'Keluar',
                    'quantity'  => $item['quantity'],
                    'reference' => 'Penjualan ' . $orderCode
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses pesanan.');
        }
        
        $history = session('order_history', []);
        $history[] = $orderCode;
        session(['order_history' => $history]);

        return redirect()->route('customer.status', $orderCode)->with('success', 'Pesanan Anda berhasil dikirim!');
    }

    
    public function status(Request $request, $code)
    {
        $order = Order::with('items.menu', 'table')->where('order_code', $code)->firstOrFail();
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => $order->status
            ]);
        }
        
        return view('customer.status', compact('order'));
    }

    public function history()
    {
        $tableId = session('customer_table_id');
        if (!$tableId) {
            return redirect()->route('customer.order')->with('error', 'Silakan scan QR Code meja terlebih dahulu.');
        }

        $table = Table::find($tableId);
        
        $query = Order::with('items.menu', 'payment')
            ->where('table_id', $tableId)
            ->orderBy('created_at', 'desc');

        if ($table && $table->last_cleared_at) {
            $query->where('created_at', '>=', $table->last_cleared_at);
        }

        $orders = $query->get();
        
        return view('customer.history', compact('orders', 'table'));
    }

    public function requestUnlock(Request $request, $id)
    {
        $table = Table::findOrFail($id);
        $table->update(['is_unlock_requested' => true]);
        
        return redirect()->back()->with('success_request', 'Request kesediaan meja telah dikirim. Silakan tunggu beberapa saat atau hubungi pelayan.');
    }
}