<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Persediaan extends Model
{
    protected $table = 'persediaan';
    
    protected $fillable = [
        'barang_id',
        'safety_stock',
        'stock'
    ];

    protected $casts = [
        'safety_stock' => 'integer',
        'stock' => 'integer'
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Menghitung safety stock otomatis berdasarkan stock saat ini
     * Formula sederhana: 20% dari stock saat ini (minimum 5)
     */
    public function calculateSafetyStock(): int
    {
        if ($this->stock == 0) {
            return 5; // Minimum safety stock
        }
        
        // 20% dari stock saat ini, minimum 5
        $calculated = max(5, (int) ($this->stock * 0.2));
        return $calculated;
    }

    /**
     * Mendapatkan warna status untuk UI
     */
    public function getSafetyStockColor(): string
    {
        if ($this->stock >= $this->safety_stock) {
            return 'success';
        } else {
            return 'danger';
        }
    }
}
