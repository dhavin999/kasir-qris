<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id', 'fk_items_order_id')
              ->references('id')->on('orders')->onDelete('cascade');
              
            $table->unsignedBigInteger('menu_id');
            $table->foreign('menu_id', 'fk_items_menu_id')
              ->references('id')->on('menus')->onDelete('cascade');

            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};
