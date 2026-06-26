<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Stock;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $menus = Menu::with('stock')->latest()->get();
        return view('admin.stocks.index', compact('menus'));
    }

    public function edit($menuId)
    {
        $menu = Menu::with('stock')->findOrFail($menuId);
        return view('admin.stocks.edit', compact('menu'));
    }

    public function history($menuId)
    {
        $menu = Menu::with(['stock.histories' => function ($query) {
            $query->latest();
        }, 'stock.histories.user'])->findOrFail($menuId);
        
        return view('admin.stocks.history', compact('menu'));
    }

    public function update(Request $request, $menuId)
    {
        $request->validate([
            'type' => 'required|in:in,out', 
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $menu = Menu::findOrFail($menuId);

        DB::transaction(function () use ($request, $menu) {
            
            $stock = Stock::firstOrCreate(
                ['menu_id' => $menu->id],
                ['current_stock' => 0]
            );

            
            $currentQuantity = $stock->current_stock;
            $adjustment = $request->quantity;

            if ($request->type === 'in') {
                $newQuantity = $currentQuantity + $adjustment;
            } else {
                if ($adjustment > $currentQuantity) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => 'Jumlah pengurangan ('.$adjustment.') melebihi stok saat ini ('.$currentQuantity.').'
                    ]);
                }
                $newQuantity = $currentQuantity - $adjustment;
            }

            
            $stock->update(['current_stock' => $newQuantity]);
            $historyType = $request->type === 'in' ? 'Masuk' : 'Keluar';
            
            StockHistory::create([
                'stock_id' => $stock->id,
                'user_id' => Auth::id(), 
                'type' => $historyType,
                'quantity' => $adjustment,
                'reference' => $request->notes ?? ($historyType === 'Masuk' ? 'Penambahan stok' : 'Pengurangan stok'),
            ]);
        });

        return redirect()->route('stocks.index')->with('success', 'Stok berhasil diperbarui dan tercatat di riwayat!');
    }
}
