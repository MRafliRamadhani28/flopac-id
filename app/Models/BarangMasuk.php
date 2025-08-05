<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'no_barang_masuk',
        'tanggal_masuk',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // Auto-generate nomor barang masuk ketika data akan disimpan
        static::creating(function ($barangMasuk) {
            if (empty($barangMasuk->no_barang_masuk)) {
                $barangMasuk->no_barang_masuk = \App\Models\DocumentCounter::generateDocumentNumber('barang_masuk', 'IN', 5);
            }
        });
        
        static::deleting(function ($barangMasuk) {
            // Delete related details first
            $barangMasuk->details()->delete();
        });
    }

    /**
     * Relasi ke User (created_by)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi many-to-many ke Barang melalui pivot table
     */
    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'barang_masuk_detail')
                    ->withPivot('qty')
                    ->withTimestamps();
    }

    /**
     * Get detail barang masuk
     */
    public function details()
    {
        return $this->hasMany(BarangMasukDetail::class);
    }
}