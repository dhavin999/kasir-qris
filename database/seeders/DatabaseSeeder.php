<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Menu;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $kasirRole = Role::create(['name' => 'Kasir']);
        $OwnerRole = Role::create(['name' => 'Owner']);

        // 2. SEED DATA USER (Hubungkan dengan Role)
        User::create([
            'role_id' => $adminRole->id,
            'name' => 'Admin Toko',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'role_id' => $kasirRole->id,
            'name' => 'Siti Kasir',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'role_id' => $OwnerRole->id,
            'name' => 'Budi Owner',
            'email' => 'Owner@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        // 3. SEED DATA KATEGORI
        $makanan = Category::create(['name' => 'Makanan Utama']);
        $minuman = Category::create(['name' => 'Minuman']);
        $snack = Category::create(['name' => 'Cemilan']);

        // 4. SEED DATA MENU (Hubungkan dengan Kategori)
        Menu::create([
            'category_id' => $makanan->id,
            'name' => 'Nasi Goreng Spesial',
            'description' => 'Nasi goreng dengan telur, ayam suwir, dan kerupuk.',
            'price' => 25000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => $makanan->id,
            'name' => 'Mie Ayam Bakso',
            'description' => 'Mie ayam kenyal dengan 3 pentol bakso sapi.',
            'price' => 20000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => $minuman->id,
            'name' => 'Es Teh Manis',
            'description' => 'Es teh segar menggunakan gula asli.',
            'price' => 5000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => $minuman->id,
            'name' => 'Kopi Susu Gula Aren',
            'description' => 'Espresso dengan susu segar dan sirup aren premium.',
            'price' => 18000,
            'is_available' => true,
        ]);
    }
}


