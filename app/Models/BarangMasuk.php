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
        static::deleting(function ($barangMasuk) {
            // Delete related details first
            $barangMasuk->details()->delete();
        });
    }

    /**
     * Generate nomor barang masuk otomatis
     */
    public static function generateNoBarangMasuk()
    {
        $lastBarangMasuk = self::orderBy('id', 'desc')->first();
        
        if (!$lastBarangMasuk) {
            return 'IN-00001';
        }
        
        $lastNumber = intval(substr($lastBarangMasuk->no_barang_masuk, 3));
        $newNumber = $lastNumber + 1;
        
        return 'IN-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
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