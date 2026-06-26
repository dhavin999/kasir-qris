<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_id');
            $table->foreign('stock_id', 'fk_histories_stock_id')
              ->references('id')->on('stocks')->onDelete('cascade');
              
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'fk_histories_user_id')
              ->references('id')->on('users')->onDelete('set null');

            $table->enum('type', ['Masuk', 'Keluar', 'Penyesuaian'])->index();
            $table->integer('quantity');
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_histories');
    }
};
