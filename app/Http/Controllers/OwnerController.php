<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        // 1. LAPORAN RINGKASAN KEUANGAN (Hanya Hitung Status 'Selesai')
        $todaySales = Order::where('status', 'Selesai')->whereDate('updated_at', today())->sum('total_price');
        $monthSales = Order::where('status', 'Selesai')->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->sum('total_price');
        $yearSales  = Order::where('status', 'Selesai')->whereYear('updated_at', now()->year)->sum('total_price');

        // 2. MENU TERLARIS (Top 5)
        $topMenus = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($query) {
                $query->where('status', 'Selesai');
            })
            ->groupBy('menu_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->with('menu')
            ->get();

        // 3. DATA GRAFIK PENJUALAN (7 Hari Terakhir)
        $chartData = Order::select(DB::raw('DATE(updated_at) as date'), DB::raw('SUM(total_price) as total'))
            ->where('status', 'Selesai')
            ->where('updated_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 4. DAFTAR TRANSAKSI UNTUK TABEL LAPORAN
        $orders = Order::with('table')->where('status', 'Selesai')->orderBy('updated_at', 'desc')->take(10)->get();

        return view('owner.dashboard', compact('todaySales', 'monthSales', 'yearSales', 'topMenus', 'chartData', 'orders'));
    }

    public function stockMonitor()
    {
        // Mengambil menu yang stoknya menipis (misal di bawah 15 item)
        $lowStocks = Menu::with('stock')
            ->whereHas('stock', function($q) {
                $q->where('current_stock', '<=', 15);
            })->get();

        return view('owner.stock', compact('lowStocks'));
    }

    public function exportExcel()
    {
        $fileName = 'Laporan_Penjualan_' . date('Y-m-d') . '.csv';
        $orders = Order::where('status', 'Selesai')->orderBy('updated_at', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Waktu Selesai', 'Kode Nota', 'Pelanggan', 'Subtotal', 'Total Bayar'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->updated_at->format('Y-m-d H:i'),
                    $order->order_code,
                    $order->customer_name,
                    $order->subtotal,
                    $order->total_price,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Order::where('status', 'Selesai');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $orders = $query->orderBy('updated_at', 'asc')->get();
        $totalRevenue = $orders->sum('total_price');

        return view('owner.print_report', compact('orders', 'totalRevenue', 'startDate', 'endDate'));
    }
}
