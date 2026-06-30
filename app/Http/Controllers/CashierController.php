<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StockHistory;
use App\Models\Category;
use App\Models\Menu;
use App\Models\OrderItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CashierController extends Controller
{
    public function incomingOrders()
    {
        $orders = Order::with('table', 'items.menu')->where('status', 'Menunggu')->orderBy('created_at', 'asc')->get();
        return view('cashier.orders.incoming', compact('orders'));
    }

    public function processingOrders()
    {
        $orders = Order::with('table', 'items.menu')->where('status', 'Diproses')->orderBy('created_at', 'asc')->get();
        return view('cashier.orders.processing', compact('orders'));
    }

    public function readyOrders()
    {
        $orders = Order::with('table', 'items.menu')->where('status', 'Siap Disajikan')->orderBy('created_at', 'asc')->get();
        return view('cashier.orders.ready', compact('orders'));
    }

    public function tables()
    {
        $tables = \App\Models\Table::orderBy('table_number', 'asc')->get();
        return view('cashier.tables.index', compact('tables'));
    }

    public function toggleTableStatus(Request $request, $id)
    {
        $table = \App\Models\Table::findOrFail($id);
        $newStatus = $table->status === 'Kosong' ? 'Terisi' : 'Kosong';
        
        $data = [
            'status' => $newStatus,
        ];

        if ($newStatus === 'Kosong') {
            $data['last_cleared_at'] = now();
        }

        $table->update($data);

        return redirect()->back()->with('success', 'Status Meja ' . $table->table_number . ' berhasil diubah menjadi ' . $newStatus . '.');
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diproses,Siap Disajikan,Selesai,Dibatalkan', // Sesuai enum orders
            'payment_method' => 'nullable|in:Tunai,QRIS',
            'amount_paid' => 'nullable|numeric'
        ]);

        $order = Order::with('items.menu.stock')->findOrFail($id);

        // Validasi state transition (Mencegah bypass pembayaran atau pembatalan ilegal)
        $validTransitions = [
            'Menunggu' => ['Diproses', 'Dibatalkan'],
            'Diproses' => ['Siap Disajikan'],
            'Siap Disajikan' => ['Selesai']
        ];

        if (!isset($validTransitions[$order->status]) || !in_array($request->status, $validTransitions[$order->status])) {
            return redirect()->back()->with('error', 'Status pesanan saat ini (' . $order->status . ') tidak dapat diubah menjadi ' . $request->status . '.');
        }

        // Validasi pembayaran sebelum transaksi untuk menghindari DB error
        if ($request->status === 'Diproses' && $order->status === 'Menunggu') {
            if (!$request->filled('payment_method')) {
                return redirect()->back()->with('error', 'Metode pembayaran harus dipilih.');
            }

            $amountPaid = $request->amount_paid ?? $order->total_price;
            if ($request->payment_method === 'Tunai' && $amountPaid < $order->total_price) {
                return redirect()->back()->with('error', 'Nominal uang tunai kurang dari total tagihan.');
            }
        }

        // Gunakan Database Transaction agar aman dari bug data korup
        DB::transaction(function () use ($order, $request) {
            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Logika Pembatalan: Kembalikan stok HANYA jika status sebelumnya Menunggu
            if ($newStatus === 'Dibatalkan' && $oldStatus === 'Menunggu') {
                foreach ($order->items as $item) {
                    $menu = $item->menu;
                    
                    if ($menu && $menu->stock) {
                        // Kembalikan current_stock
                        $menu->stock->increment('current_stock', $item->quantity);

                        // Catat pembatalan
                        StockHistory::create([
                            'stock_id'  => $menu->stock->id,
                            'user_id'   => auth()->id(), // ID Kasir
                            'type'      => 'Masuk',
                            'quantity'  => $item->quantity,
                            'reference' => 'Pembatalan ' . $order->order_code 
                        ]);
                    }
                }
            }

            // Jika pesanan diubah dari Menunggu ke Diproses, catat pembayaran
            if ($newStatus === 'Diproses' && $oldStatus === 'Menunggu') {
                $amountPaid = $request->amount_paid ?? $order->total_price;
                $amountReturn = max(0, $amountPaid - $order->total_price);

                \App\Models\Payment::create([
                    'order_id' => $order->id,
                    'user_id' => auth()->id(),
                    'payment_method' => $request->payment_method,
                    'amount_paid' => $amountPaid,
                    'amount_return' => $amountReturn,
                    'status' => 'Lunas',
                    'payment_date' => now(),
                ]);
            }

            // C. Update status utama pesanan
            $order->update(['status' => $newStatus]);

            // D. Jika pesanan diubah ke "Diproses" (sedang dimasak), otomatis kunci mejanya
            if ($newStatus === 'Diproses' && $order->table) {
                $order->table->update(['status' => 'Terisi']);
            }
        });

        return redirect()->back()->with('success', 'Status pesanan ' . $order->order_code . ' berhasil diperbarui!');
    }

    public function history()
    {
        $completedOrders = Order::with('table')
            ->whereIn('status', ['Selesai', 'Dibatalkan'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('cashier.history', compact('completedOrders'));
    }

    public function printReceipt($id)
    {
        $order = Order::with('table', 'items.menu', 'payment')->findOrFail($id);
        return view('cashier.print', compact('order'));
    }

    public function printKitchenReceipt($id)
    {
        $order = Order::with('table', 'items.menu')->findOrFail($id);
        return view('cashier.print_kitchen', compact('order'));
    }

    public function createOrder()
    {
        $categories = Category::all();
        $menus = Menu::with('stock')->where('is_available', true)->get();
        return view('cashier.create_order', compact('categories', 'menus'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'cart_data' => 'required|string', 
            'payment_method' => 'required|in:Tunai,QRIS',
            'amount_paid' => 'nullable|numeric'
        ]);

        $cart = json_decode($request->cart_data, true);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja masih kosong!');
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

            $totalPrice = $subtotal;
            $orderCode = 'TA-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            // Buat data Order Utama untuk Take Away
            $order = Order::create([
                'table_id' => null, // Take Away tidak punya meja
                'order_type' => 'Take Away',
                'order_code' => $orderCode,
                'customer_name' => $request->customer_name,
                'subtotal' => $subtotal,
                'total_price' => $totalPrice,
                'status' => 'Diproses', // Langsung diproses (atau bisa Selesai jika lunas)
            ]);

            $amountPaid = $request->amount_paid ?? $totalPrice; // Jika QRIS, otomatis lunas sesuai tagihan
            
            if ($request->payment_method === 'Tunai' && $amountPaid < $totalPrice) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Nominal uang tunai kurang dari total tagihan.');
            }

            $amountReturn = max(0, $amountPaid - $totalPrice);

            \App\Models\Payment::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'payment_method' => $request->payment_method,
                'amount_paid' => $amountPaid,
                'amount_return' => $amountReturn,
                'status' => 'Lunas',
                'payment_date' => now(),
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
                    'user_id'   => auth()->id(), // ID Kasir
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

        return redirect()->route('kasir.dashboard')->with('success', 'Pesanan Take Away berhasil dibuat!');
    }

    public function endOfDay()
    {
        $todayOrders = Order::with('payment')
            ->where('status', 'Selesai')
            ->whereDate('updated_at', today())
            ->get();

        $totalOrders = $todayOrders->count();
        $dineInCount = $todayOrders->where('order_type', '!=', 'Take Away')->count();
        $takeAwayCount = $todayOrders->where('order_type', 'Take Away')->count();
        
        $totalSubtotal = $todayOrders->sum('subtotal');
        $totalRevenue = $todayOrders->sum('total_price');

        $totalTunai = 0;
        $totalQRIS = 0;
        $totalAmountPaidTunai = 0;
        $totalAmountReturnTunai = 0;

        foreach ($todayOrders as $order) {
            if ($order->payment) {
                if ($order->payment->payment_method === 'Tunai') {
                    $totalTunai += $order->total_price;
                    $totalAmountPaidTunai += $order->payment->amount_paid;
                    $totalAmountReturnTunai += $order->payment->amount_return;
                } elseif ($order->payment->payment_method === 'QRIS') {
                    $totalQRIS += $order->total_price;
                }
            }
        }

        return view('cashier.print_end_of_day', compact(
            'todayOrders', 'totalOrders', 'dineInCount', 'takeAwayCount',
            'totalSubtotal', 'totalRevenue', 'totalTunai', 'totalQRIS', 'totalAmountPaidTunai', 'totalAmountReturnTunai'
        ));
    }
}
