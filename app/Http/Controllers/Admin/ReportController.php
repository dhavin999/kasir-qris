<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default: 7 hari terakhir jika tidak ada filter
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(6)->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        $orders = Order::with('table')
            ->where('status', 'Selesai')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->orderBy('updated_at', 'desc')
            ->get();

        $totalRevenue = $orders->sum('total_price');
        $totalOrders = $orders->count();

        return view('owner.reports.index', compact('orders', 'startDate', 'endDate', 'totalRevenue', 'totalOrders'));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(6)->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        $fileName = 'Laporan_Penjualan_' . $startDate->format('Ymd') . '_sd_' . $endDate->format('Ymd') . '.csv';
        
        $orders = Order::where('status', 'Selesai')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->orderBy('updated_at', 'desc')
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Waktu Pembayaran', 'Tipe Order', 'Kode Nota', 'Pelanggan', 'Subtotal', 'Total Bayar'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $grandTotal = 0;
            foreach ($orders as $order) {
                $grandTotal += $order->total_price;
                fputcsv($file, [
                    $order->updated_at->format('Y-m-d H:i'),
                    $order->order_type ?? 'Dine In',
                    $order->order_code,
                    $order->customer_name,
                    $order->subtotal,
                    $order->total_price,
                ]);
            }
            // Tambahkan baris total
            fputcsv($file, ['', '', '', '', '', 'TOTAL PENDAPATAN', $grandTotal]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(6)->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        $orders = Order::with('table')
            ->where('status', 'Selesai')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->orderBy('updated_at', 'asc')
            ->get();

        $totalRevenue = $orders->sum('total_price');

        return view('owner.reports.print', compact('orders', 'totalRevenue', 'startDate', 'endDate'));
    }
}


