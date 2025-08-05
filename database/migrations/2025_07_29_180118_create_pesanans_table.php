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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('no_pesanan')->unique();
            $table->string('nama_pelanggan');
            $table->text('alamat');
            $table->string('model');
            $table->string('sumber');
            $table->text('catatan')->nullable();
            $table->enum('status', ['Pending', 'Diproses', 'Selesai'])->default('Pending');
            $table->date('tanggal_pesanan');
            $table->date('tenggat_pesanan');
            $table->unsignedBigInteger('diproses_oleh')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('diproses_oleh')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
