<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan_persediaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            $table->foreignId('persediaan_id')->constrained('persediaan')->onDelete('cascade');
            $table->integer('jumlah_dipakai')->default(0);
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['pesanan_id', 'persediaan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_persediaan');
    }
};
