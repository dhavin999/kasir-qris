<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_id');
            $table->foreign('table_id', 'fk_orders_table_id')
              ->references('id')->on('tables')->onDelete('cascade');
              
            $table->string('order_code', 20)->unique()->index();
            $table->string('customer_name', 100)->index();
            $table->decimal('subtotal', 10, 2);

            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['Menunggu', 'Diproses', 'Siap Disajikan', 'Selesai', 'Dibatalkan'])->default('Menunggu')->index();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};