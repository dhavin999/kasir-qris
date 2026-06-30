# Peta Belajar Sidang - kasir-qris

## 1. Gambaran Besar
Aplikasi ini adalah sistem kasir/resto berbasis Laravel dengan 4 peran utama:
- Admin: kelola kategori, menu, stok, meja, user
- Kasir: proses order, ubah status pesanan, cetak struk, lihat histori
- Owner: lihat dashboard dan laporan penjualan
- Customer: scan QR meja, pesan menu, cek status pesanan

## 2. Urutan File yang Harus Dibaca
1. [routes/web.php](routes/web.php)
2. Model relasi:
   - [app/Models/Category.php](app/Models/Category.php)
   - [app/Models/Menu.php](app/Models/Menu.php)
   - [app/Models/Order.php](app/Models/Order.php)
   - [app/Models/Stock.php](app/Models/Stock.php)
   - [app/Models/Table.php](app/Models/Table.php)
   - [app/Models/Payment.php](app/Models/Payment.php)
3. Controller inti:
   - [app/Http/Controllers/CustomerOrderController.php](app/Http/Controllers/CustomerOrderController.php)
   - [app/Http/Controllers/CashierController.php](app/Http/Controllers/CashierController.php)
   - [app/Http/Controllers/Admin/CategoryController.php](app/Http/Controllers/Admin/CategoryController.php)
   - [app/Http/Controllers/Admin/MenuController.php](app/Http/Controllers/Admin/MenuController.php)
   - [app/Http/Controllers/Admin/StockController.php](app/Http/Controllers/Admin/StockController.php)
4. Migration inti:
   - [database/migrations/2026_06_09_061213_create_categories_table.php](database/migrations/2026_06_09_061213_create_categories_table.php)
   - [database/migrations/2026_06_09_061224_create_menus_table.php](database/migrations/2026_06_09_061224_create_menus_table.php)
   - [database/migrations/2026_06_09_061234_create_tables_table.php](database/migrations/2026_06_09_061234_create_tables_table.php)
   - [database/migrations/2026_06_09_061312_create_orders_table.php](database/migrations/2026_06_09_061312_create_orders_table.php)
   - [database/migrations/2026_06_09_061318_create_order_items_table.php](database/migrations/2026_06_09_061318_create_order_items_table.php)
   - [database/migrations/2026_06_09_061325_create_payments_table.php](database/migrations/2026_06_09_061325_create_payments_table.php)
   - [database/migrations/2026_06_09_061339_create_stocks_table.php](database/migrations/2026_06_09_061339_create_stocks_table.php)
   - [database/migrations/2026_06_09_061347_create_stock_histories_table.php](database/migrations/2026_06_09_061347_create_stock_histories_table.php)

## 3. Relasi Data yang Wajib Diingat
- Category hasMany Menu
- Menu belongsTo Category
- Menu hasOne Stock
- Menu hasMany OrderItem
- Order belongsTo Table
- Order hasMany OrderItem
- Order hasOne Payment
- Stock belongsTo Menu
- Stock hasMany StockHistory
- User belongsTo Role
- User hasMany Payment
- User hasMany StockHistory

## 4. Alur Utama yang Sering Ditanya
### A. Customer order
- Customer scan QR meja
- Sistem simpan nomor meja ke session
- Customer pilih menu dan checkout
- Order dibuat dengan status Menunggu
- Stok menu langsung berkurang
- Riwayat stok dicatat

### B. Kasir proses order
- Kasir lihat order masuk, diproses, dan siap disajikan
- Kasir ubah status order sesuai alur
- Saat order dari Menunggu ke Diproses, payment dicatat
- Jika order dibatalkan, stok dikembalikan
- Jika order diproses, status meja ikut berubah

### C. Admin kelola master data
- Admin buat kategori
- Admin buat menu
- Admin set stok menu
- Admin kelola meja dan user

### D. Owner lihat laporan
- Owner baca dashboard dan report penjualan
- Data laporan diambil dari order dan payment

## 5. Penjelasan yang Bisa Kamu Sampaikan Saat Sidang
- Stok dipotong saat order dibuat supaya persediaan langsung akurat.
- Transaction database dipakai supaya data order, item, payment, dan stok tidak setengah tersimpan.
- Role dipakai agar tiap pengguna hanya melihat fitur yang sesuai.
- Status order dibuat bertahap supaya alurnya terkontrol.
- Riwayat stok disimpan agar perubahan persediaan bisa dilacak.

## 6. 3 Hari Sebelum Sidang
### Hari 1
- Baca routes dan relasi model
- Hafalkan peran Admin, Kasir, Owner, Customer
- Pahami struktur tabel utama

### Hari 2
- Ikuti satu alur full dari customer sampai kasir
- Catat file mana yang jalan di setiap langkah
- Latih jelasin alur stok dan payment

### Hari 3
- Buat ringkasan 1 halaman dari file ini
- Latih presentasi 5 sampai 10 menit
- Siapkan jawaban untuk pertanyaan tentang validasi, relasi, dan transaksi

## 7. Jawaban Singkat Untuk Pertanyaan Umum
- Kenapa pakai Laravel? Karena cepat untuk routing, MVC, ORM, dan validasi.
- Kenapa ada table status? Untuk tahu meja sedang kosong atau terisi.
- Kenapa order dan order item dipisah? Supaya header pesanan dan detail item tidak bercampur.
- Kenapa ada payment terpisah? Supaya data pembayaran satu order lebih rapi.
- Kenapa stok ada history? Supaya semua perubahan stok tercatat.

## 8. Penjelasan Tiap File Dengan Bahasa Gampang
### routes/web.php
Ini peta jalan aplikasi. Dari file ini kamu bisa lihat siapa boleh akses apa, misalnya admin, kasir, owner, dan customer.

### app/Models/Category.php
Ini model untuk kategori menu. Isinya sederhana: satu kategori bisa punya banyak menu.

### app/Models/Menu.php
Ini model menu makanan atau minuman. Menu terhubung ke kategori, stok, dan item pesanan.

### app/Models/Order.php
Ini model pesanan. Satu order bisa punya banyak item dan satu data pembayaran.

### app/Models/Stock.php
Ini model stok per menu. Dipakai untuk tahu sisa barang yang masih tersedia.

### app/Models/Table.php
Ini model meja. Dipakai untuk menandai meja kosong atau terisi, dan untuk QR meja customer.

### app/Models/Payment.php
Ini model pembayaran. Isinya metode bayar, nominal masuk, dan kembalian.

### app/Http/Controllers/CustomerOrderController.php
Ini pengatur alur customer. Mulai dari scan meja, pilih menu, checkout, sampai cek status pesanan.

### app/Http/Controllers/CashierController.php
Ini pengatur alur kasir. Di sini kasir melihat order masuk, mengubah status, mencatat pembayaran, dan mencetak struk.

### app/Http/Controllers/Admin/CategoryController.php
Ini pengatur kategori. Admin bisa tambah, ubah, dan hapus kategori menu.

### app/Http/Controllers/Admin/MenuController.php
Ini pengatur menu. Admin bisa membuat dan memperbarui data menu, termasuk gambar dan harga.

### app/Http/Controllers/Admin/StockController.php
Ini pengatur stok. Admin bisa tambah atau kurangi stok dan melihat riwayat perubahan stok.

### database/migrations/2026_06_09_061213_create_categories_table.php
Ini struktur tabel kategori di database.

### database/migrations/2026_06_09_061224_create_menus_table.php
Ini struktur tabel menu. Di sini ada kategori, harga, dan status aktif/tidak aktif.

### database/migrations/2026_06_09_061234_create_tables_table.php
Ini struktur tabel meja. Ada nomor meja, QR code, dan status meja.

### database/migrations/2026_06_09_061312_create_orders_table.php
Ini struktur tabel order. Di sini disimpan kode order, nama customer, total harga, dan status.

### database/migrations/2026_06_09_061318_create_order_items_table.php
Ini struktur detail isi order. Satu order bisa punya banyak menu di dalamnya.

### database/migrations/2026_06_09_061325_create_payments_table.php
Ini struktur pembayaran. Satu order hanya punya satu data payment.

### database/migrations/2026_06_09_061339_create_stocks_table.php
Ini struktur stok menu. Di sini ada stok sekarang dan stok minimum.

### database/migrations/2026_06_09_061347_create_stock_histories_table.php
Ini riwayat stok. Semua masuk dan keluar stok dicatat di sini.

## 9. Alur Data End-to-End Yang Perlu Kamu Hafal
### 1) Customer scan QR meja
- Customer membuka halaman order dari QR code meja.
- Sistem membaca nomor meja dari query parameter `meja`.
- Nomor meja disimpan ke session supaya identitas meja tetap kebawa.

### 2) Customer pilih menu
- Sistem menampilkan kategori dan menu yang masih aktif.
- Menu yang tampil hanya yang `is_available = true`.
- Stok menu ikut ditampilkan supaya customer dan sistem tahu ketersediaan.

### 3) Customer checkout
- Customer mengirim nama dan isi keranjang.
- Sistem validasi data keranjang, kuantitas, dan stok.
- Harga dari database dipakai, bukan harga dari client.
- Order dibuat dengan status `Menunggu`.
- Order item dibuat satu per satu.
- Stok dikurangi langsung.
- Riwayat stok dibuat dengan tipe `Keluar`.

### 4) Kasir memproses pesanan
- Kasir membuka daftar pesanan masuk.
- Kasir mengubah status pesanan dari `Menunggu` ke `Diproses`.
- Saat status berubah, pembayaran dicatat.
- Jika pesanan dibatalkan, stok dikembalikan.
- Jika pesanan diproses, status meja diubah jadi `Terisi`.

### 5) Owner melihat hasil
- Owner membuka dashboard dan laporan.
- Data laporan diambil dari order yang selesai dan payment yang sudah tercatat.

## 10. File Yang Paling Penting Saat Sidang
Kalau waktu kamu sempit, fokus ke file ini dulu karena paling sering muncul di penjelasan:
- [routes/web.php](routes/web.php): menunjukkan semua jalur akses aplikasi.
- [app/Http/Controllers/CustomerOrderController.php](app/Http/Controllers/CustomerOrderController.php): alur customer.
- [app/Http/Controllers/CashierController.php](app/Http/Controllers/CashierController.php): alur kasir dan stok.
- [app/Models/Order.php](app/Models/Order.php): pusat data pesanan.
- [app/Models/Menu.php](app/Models/Menu.php): pusat data menu dan stok.
- [database/migrations/2026_06_09_061312_create_orders_table.php](database/migrations/2026_06_09_061312_create_orders_table.php): struktur order.
- [database/migrations/2026_06_09_061339_create_stocks_table.php](database/migrations/2026_06_09_061339_create_stocks_table.php): struktur stok.

## 11. Hal Yang Wajib Kamu Bisa Jelaskan
### Kenapa stok dikurangi saat order dibuat?
Karena sistem ingin langsung mengunci ketersediaan barang. Kalau stok dikurangi di awal, data persediaan lebih akurat dan mencegah pesanan melebihi stok.

### Kenapa order punya tabel order_items?
Karena satu pesanan bisa berisi banyak menu. Kalau semua disimpan di satu tabel, data jadi tidak rapi dan sulit dihitung per item.

### Kenapa payment dipisah dari order?
Karena pembayaran punya detail sendiri, seperti metode bayar, jumlah uang diterima, dan kembalian.

### Kenapa ada stock_history?
Karena perubahan stok harus bisa dilacak. Dengan riwayat, kamu bisa tahu stok berkurang karena penjualan atau bertambah karena penyesuaian.

### Kenapa pakai transaction database?
Supaya kalau salah satu proses gagal, seluruh proses bisa dibatalkan. Ini mencegah data order masuk tapi payment atau stok tidak tersimpan dengan benar.

## 12. Contoh Jawaban Kalau Dosen Tanya
### Pertanyaan: Alur aplikasi ini bagaimana?
Jawaban: Aplikasi ini punya alur customer, kasir, admin, dan owner. Customer scan QR meja lalu pesan menu. Pesanan masuk ke kasir untuk diproses. Admin mengelola menu, kategori, meja, dan stok. Owner melihat laporan penjualan.

### Pertanyaan: Kenapa ada role?
Jawaban: Role dipakai supaya hak akses tiap pengguna berbeda. Admin hanya mengelola data master, kasir memproses transaksi, owner melihat laporan, dan customer hanya memesan.

### Pertanyaan: Kenapa stok langsung berkurang saat checkout?
Jawaban: Supaya data stok tetap real-time. Kalau tidak langsung dikurangi, stok di sistem bisa lebih besar dari stok sebenarnya.

### Pertanyaan: Apa fungsi order_code?
Jawaban: order_code adalah kode unik untuk membedakan setiap pesanan agar mudah dicari, ditampilkan, dan dicetak di struk.

### Pertanyaan: Kenapa menu bisa nonaktif?
Jawaban: Supaya admin bisa menonaktifkan menu yang sedang habis atau tidak dijual sementara tanpa harus menghapus datanya.

## 13. Checklist Hafalan Cepat
- Saya tahu peran Admin, Kasir, Owner, Customer.
- Saya tahu alur dari scan QR sampai order selesai.
- Saya tahu relasi Category, Menu, Order, Payment, Stock, dan Table.
- Saya tahu kenapa stok dan riwayat stok penting.
- Saya tahu kenapa payment dipisah dari order.
- Saya tahu kenapa transaksi database dipakai.

## 14. Ringkasan Super Singkat
Aplikasi ini adalah sistem kasir Laravel untuk restoran atau cafe. Customer pesan lewat QR meja, kasir memproses pesanan, admin mengelola master data, dan owner melihat laporan. Data utamanya tersimpan di category, menu, table, order, order_items, payment, stock, dan stock_histories.
