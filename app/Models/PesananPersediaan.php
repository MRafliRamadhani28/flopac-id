<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananPersediaan extends Model
{
    use HasFactory;

    protected $table = 'pesanan_persediaan';

    protected $fillable = [
        'pesanan_id',
        'persediaan_id',
        'jumlah_dipakai',
    ];

    protected $casts = [
        'jumlah_dipakai' => 'integer',
    ];

    /**
     * Relasi ke Pesanan
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    /**
     * Relasi ke Persediaan
     */
    public function persediaan()
    {
        return $this->belongsTo(Persediaan::class);
    }
}