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

    /**
     * Relasi one-to-many ke BarangMasukDetail
     */
    public function barangMasukDetails()
    {
        return $this->hasMany(BarangMasukDetail::class);
    }

    /**
     * Relasi one-to-many ke PenyesuaianPersediaanDetail
     */
    public function penyesuaianPersediaanDetails()
    {
        return $this->hasMany(PenyesuaianPersediaanDetail::class);
    }

    /**
     * Check if this barang can be safely deleted
     */
    public function canBeDeleted()
    {
        return $this->barangMasukDetails()->count() === 0 && 
               $this->penyesuaianPersediaanDetails()->count() === 0;
    }

    /**
     * Get the usage details for this barang
     */
    public function getUsageDetails()
    {
        $details = [];
        
        $barangMasukCount = $this->barangMasukDetails()->count();
        if ($barangMasukCount > 0) {
            $details[] = "{$barangMasukCount} transaksi barang masuk";
        }
        
        $penyesuaianCount = $this->penyesuaianPersediaanDetails()->count();
        if ($penyesuaianCount > 0) {
            $details[] = "{$penyesuaianCount} transaksi penyesuaian persediaan";
        }
        
        return $details;
    }
}
