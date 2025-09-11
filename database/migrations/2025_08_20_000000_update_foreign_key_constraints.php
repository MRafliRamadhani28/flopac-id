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
        // Update penyesuaian_persediaan_detail foreign key to restrict delete
        Schema::table('penyesuaian_persediaan_detail', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['barang_id']);
            
            // Add new foreign key with restrict delete (prevents deletion if referenced)
            $table->foreign('barang_id')
                  ->references('id')
                  ->on('barangs')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });

        // Update barang_masuk_detail foreign key to restrict delete
        Schema::table('barang_masuk_detail', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['barang_id']);
            
            // Add new foreign key with restrict delete (prevents deletion if referenced)
            $table->foreign('barang_id')
                  ->references('id')
                  ->on('barangs')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });

        // Update persediaan foreign key to cascade delete
        Schema::table('persediaan', function (Blueprint $table) {
            // Drop existing foreign key if it exists
            try {
                $table->dropForeign(['barang_id']);
            } catch (\Exception $e) {
                // Foreign key might not exist, continue
            }
            
            // Add new foreign key with cascade delete (deletes persediaan when barang is deleted)
            $table->foreign('barang_id')
                  ->references('id')
                  ->on('barangs')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert penyesuaian_persediaan_detail foreign key
        Schema::table('penyesuaian_persediaan_detail', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->foreign('barang_id')->references('id')->on('barangs');
        });

        // Revert barang_masuk_detail foreign key
        Schema::table('barang_masuk_detail', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->foreign('barang_id')->references('id')->on('barangs');
        });

        // Revert persediaan foreign key
        Schema::table('persediaan', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->foreign('barang_id')->references('id')->on('barangs');
        });
    }
};
