<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->latest()->get();
        $categories = Category::all();
        return view('admin.menus.index', compact('menus', 'categories'));
    }

    public function create()
    {
        // Ambil semua kategori untuk pilihan dropdown
        $categories = Category::all();
        return view('admin.menus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:250',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        Menu::create($data);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:250',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
        
            if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
            }

            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus!');
    }
}
