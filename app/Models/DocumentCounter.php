<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocumentCounter extends Model
{
    protected $fillable = [
        'document_type',
        'prefix', 
        'current_number'
    ];

    /**
     * Generate nomor dokumen baru dengan increment yang aman
     * 
     * @param string $documentType - tipe dokumen (barang_masuk, penyesuaian_persediaan)
     * @param string $prefix - prefix nomor (IN, ADJ)
     * @param int $paddingLength - panjang padding angka (default: 5)
     * @return string
     */
    public static function generateDocumentNumber($documentType, $prefix, $paddingLength = 5)
    {
        return DB::transaction(function () use ($documentType, $prefix, $paddingLength) {
            // Update counter dan ambil nomor baru dalam satu operasi atomik
            $counter = self::where('document_type', $documentType)->first();
            
            if (!$counter) {
                // Jika belum ada counter, buat yang baru
                self::create([
                    'document_type' => $documentType,
                    'prefix' => $prefix,
                    'current_number' => 1
                ]);
                $nextNumber = 1;
            } else {
                // Increment counter
                $counter->increment('current_number');
                $nextNumber = $counter->current_number;
            }
            
            // Format nomor dengan padding
            return $prefix . '-' . str_pad($nextNumber, $paddingLength, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Reset counter untuk testing atau maintenance
     * 
     * @param string $documentType
     * @param int $startNumber
     * @return bool
     */
    public static function resetCounter($documentType, $startNumber = 0)
    {
        return self::where('document_type', $documentType)
            ->update(['current_number' => $startNumber]);
    }

    /**
     * Get current number untuk dokumen tertentu
     * 
     * @param string $documentType
     * @return int
     */
    public static function getCurrentNumber($documentType)
    {
        $counter = self::where('document_type', $documentType)->first();
        return $counter ? $counter->current_number : 0;
    }

    /**
     * Preview nomor berikutnya yang akan di-generate tanpa menggunakan counter
     * 
     * @param string $documentType
     * @param string $prefix
     * @param int $paddingLength
     * @return string
     */
    public static function previewNextNumber($documentType, $prefix, $paddingLength = 5)
    {
        $counter = self::where('document_type', $documentType)->first();
        $nextNumber = $counter ? ($counter->current_number + 1) : 1;
        
        return $prefix . '-' . str_pad($nextNumber, $paddingLength, '0', STR_PAD_LEFT);
    }
}
