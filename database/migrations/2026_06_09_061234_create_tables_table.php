<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number', 10)->unique()->index();
            $table->string('qr_code_path')->nullable();
            $table->enum('status', ['Kosong', 'Terisi'])->default('Kosong')->index();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tables');
    }
};
