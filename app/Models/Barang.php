<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'nama_barang',
        'warna',
        'satuan',
    ];

    /**
     * Boot method untuk auto-create persediaan
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($barang) {
            // Auto-create persediaan entry ketika barang baru dibuat
            $barang->persediaan()->create([
                'safety_stock' => 0,
                'stock' => 0
            ]);
        });
    }

    /**
     * Relasi many-to-many ke BarangMasuk melalui pivot table
     */
    public function barangMasuks()
    {
        return $this->belongsToMany(BarangMasuk::class, 'barang_masuk_detail')
                    ->withPivot('qty')
                    ->withTimestamps();
    }

    /**
     * Relasi one-to-one ke Persediaan
     */
    public function persediaan()
    {
        return $this->hasOne(Persediaan::class);
    }
}
