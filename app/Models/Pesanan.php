<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_pesanan',
        'nama_pelanggan',
        'alamat',
        'model',
        'sumber',
        'catatan',
        'status',
        'tanggal_pesanan',
        'tenggat_pesanan',
        'diproses_oleh',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pesanan' => 'date',
        'tenggat_pesanan' => 'date',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // Auto-generate nomor pesanan ketika data akan disimpan
        static::creating(function ($pesanan) {
            if (empty($pesanan->no_pesanan)) {
                $pesanan->no_pesanan = \App\Models\DocumentCounter::generateDocumentNumber('pesanan', 'PSN', 5);
            }
        });
        
        static::deleting(function ($pesanan) {
            // Additional cleanup if needed
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
     * Relasi ke User (diproses_oleh)
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    /**
     * Relasi ke PesananPersediaan (pivot table untuk stock usage tracking)
     */
    public function persediaanUsage()
    {
        return $this->hasMany(PesananPersediaan::class);
    }

    /**
     * Relasi many-to-many ke Persediaan melalui pivot table
     */
    public function persediaan()
    {
        return $this->belongsToMany(Persediaan::class, 'pesanan_persediaan')
                    ->withPivot('jumlah_dipakai')
                    ->withTimestamps();
    }

    /**
     * Get status color for display
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Pending' => 'warning',
            'Diproses' => 'info',
            'Selesai' => 'success',
            default => 'secondary'
        };
    }

    /**
     * Check if pesanan is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->tenggat_pesanan < now()->toDateString() && $this->status !== 'Selesai';
    }
}
