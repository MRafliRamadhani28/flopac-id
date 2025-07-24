<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyesuaianPersediaanDetail extends Model
{
    protected $table = 'penyesuaian_persediaan_detail';
    
    protected $fillable = [
        'penyesuaian_persediaan_id',
        'barang_id',
        'stock_sebelum',
        'stock_penyesuaian',
        'stock_sesudah',
        'jenis_penyesuaian'
    ];

    protected $casts = [
        'stock_sebelum' => 'integer',
        'stock_penyesuaian' => 'integer',
        'stock_sesudah' => 'integer'
    ];

    public function penyesuaianPersediaan(): BelongsTo
    {
        return $this->belongsTo(PenyesuaianPersediaan::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
