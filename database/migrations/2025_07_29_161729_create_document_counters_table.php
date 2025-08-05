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
        Schema::create('document_counters', function (Blueprint $table) {
            $table->id();
            $table->string('document_type', 50)->unique(); // 'barang_masuk', 'penyesuaian_persediaan', etc
            $table->string('prefix', 10); // 'IN', 'ADJ', etc
            $table->unsignedBigInteger('current_number')->default(0); // Counter yang akan selalu increment
            $table->timestamps();
        });
        
        // Insert initial data untuk dokumen yang sudah ada
        DB::table('document_counters')->insert([
            [
                'document_type' => 'barang_masuk',
                'prefix' => 'IN',
                'current_number' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'document_type' => 'penyesuaian_persediaan',
                'prefix' => 'ADJ',
                'current_number' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'document_type' => 'pesanan',
                'prefix' => 'PSN',
                'current_number' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_counters');
    }
};
