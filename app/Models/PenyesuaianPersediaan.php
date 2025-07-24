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
                $model->no_penyesuaian_persediaan = self::generateNoPenyesuaian();
            }
        });
    }

    public static function generateNoPenyesuaian(): string
    {
        $latestRecord = self::orderBy('id', 'desc')->first();
        
        if (!$latestRecord) {
            return 'ADJ-00001';
        }
        
        $latestNumber = (int) substr($latestRecord->no_penyesuaian_persediaan, 4);
        $newNumber = $latestNumber + 1;
        
        return 'ADJ-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}
