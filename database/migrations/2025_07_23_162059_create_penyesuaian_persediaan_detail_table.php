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
        Schema::create('penyesuaian_persediaan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyesuaian_persediaan_id')->constrained('penyesuaian_persediaan')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('stock_sebelum');
            $table->integer('stock_penyesuaian');
            $table->integer('stock_sesudah');
            $table->enum('jenis_penyesuaian', ['penambahan', 'pengurangan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyesuaian_persediaan_detail');
    }
};
