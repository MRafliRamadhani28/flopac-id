<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenyesuaianPersediaan extends Model
{
    protected $table = 'penyesuaian_persediaan';
    
    protected $fillable = [
        'no_penyesuaian_persediaan',
        'tanggal_penyesuaian',
        'keterangan',
        'created_by'
    ];

    protected $casts = [
        'tanggal_penyesuaian' => 'date'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PenyesuaianPersediaanDetail::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->no_penyesuaian_persediaan)) {
                $model->no_penyesuaian_persediaan = \App\Models\DocumentCounter::generateDocumentNumber('penyesuaian_persediaan', 'ADJ', 5);
            }
        });
    }
}
