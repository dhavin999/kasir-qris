<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unique();
            $table->foreign('order_id', 'fk_payments_order_id')
              ->references('id')->on('orders')->onDelete('cascade');
              
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'fk_payments_user_id')
              ->references('id')->on('users')->onDelete('set null');

            $table->enum('payment_method', ['Tunai', 'QRIS'])->index();
            $table->decimal('amount_paid', 10, 2);
            $table->decimal('amount_return', 10, 2)->default(0.00);
            $table->enum('status', ['Belum Bayar', 'Lunas', 'Dibatalkan'])->default('Belum Bayar')->index();
            $table->timestamp('payment_date')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
