<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use App\Models\PesananPersediaan;
use App\Models\Notification;

class Persediaan extends Model
{
    protected $table = 'persediaan';
    
    protected $fillable = [
        'barang_id',
        'safety_stock',
        'stock',
        'dipakai'
    ];

    protected $casts = [
        'safety_stock' => 'integer',
        'stock' => 'integer',
        'dipakai' => 'integer'
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Menghitung Safety Stock berdasarkan rumus: Safety Stock = K × σD × √LT
     * K = Service Level Factor (Z-score)
     * σD = Standard Deviasi dari demand harian
     * LT = Lead Time (dalam hari)
     */
    public function calculateSafetyStock(): array
    {
        // Konfigurasi parameter dari environment atau default
        $K = (float) env('SAFETY_STOCK_K', 1.65);
        $LT = (int) env('SAFETY_STOCK_LT', 7);
        $days = (int) env('SAFETY_STOCK_ANALYSIS_DAYS', 90);

        // Ambil data penggunaan barang per hari dalam periode analisis
        $usageData = PesananPersediaan::with('pesanan')
            ->where('persediaan_id', $this->id)
            ->whereHas('pesanan', function($query) use ($days) {
                $query->where('tanggal_pesanan', '>=', now()->subDays($days))
                      ->whereIn('status', ['Selesai']);
            })
            ->get()
            ->groupBy(function($item) {
                return $item->pesanan->tanggal_pesanan->format('Y-m-d');
            })
            ->map(function($group) {
                return $group->sum('jumlah_dipakai');
            })
            ->values()
            ->toArray();

        // Jika tidak ada data, return dengan pesan error
        if (empty($usageData)) {
            return [
                'success' => false,
                'message' => "Tidak ada data penggunaan dalam {$days} hari terakhir",
                'safety_stock' => 0,
                'parameters' => [
                    'K' => $K,
                    'LT' => $LT,
                    'analysis_days' => $days
                ]
            ];
        }

        // Hitung rata-rata demand per hari (μD)
        $meanDemand = array_sum($usageData) / count($usageData);

        // Hitung standard deviasi demand (σD)
        $variance = 0;
        foreach ($usageData as $dailyUsage) {
            $variance += pow($dailyUsage - $meanDemand, 2);
        }
        $standardDeviation = sqrt($variance / count($usageData));

        // Rumus Safety Stock = K × σD × √LT
        $safetyStock = $K * $standardDeviation * sqrt($LT);

        return [
            'success' => true,
            'safety_stock' => round($safetyStock, 0), // Bulatkan ke integer
            'parameters' => [
                'K' => $K,
                'standard_deviation' => round($standardDeviation, 2),
                'LT' => $LT,
                'mean_demand' => round($meanDemand, 2),
                'analysis_days' => $days,
                'data_points' => count($usageData)
            ],
            'raw_usage_data' => $usageData
        ];
    }

    /**
     * Update safety stock dengan perhitungan otomatis
     */
    public function updateSafetyStock(): bool
    {
        $calculation = $this->calculateSafetyStock();
        
        if ($calculation['success']) {
            $this->safety_stock = $calculation['safety_stock'];
            return $this->save();
        }
        
        return false;
    }

    /**
     * Mendapatkan status safety stock
     */
    public function getSafetyStockStatus(): array
    {
        $currentStock = $this->stock;
        $safetyStock  = $this->safety_stock;
        $minimumStock = $this->safety_stock + 5;

        if (($currentStock == 0) && ($safetyStock == 0)) {
            return [
                'status' => 'default',
                'color' => 'secondary',
                'message' => 'Belum ada stok'
            ];
        } elseif ($safetyStock == 0) {
            return [
                'status' => 'safe',
                'color' => 'success',
                'message' => 'Belum ada pemakaian'
            ];
        }

        if ($currentStock > $minimumStock) {
            return [
                'status' => 'safe',
                'color' => 'success',
                'message' => 'Stok aman'
            ];
        } elseif (($currentStock > $safetyStock) && ($currentStock <= $minimumStock)) {
            return [
                'status' => 'warning',
                'color' => 'warning', 
                'message' => 'Stok mendekati batas minimum'
            ];
        } else {
            return [
                'status' => 'critical',
                'color' => 'danger',
                'message' => 'Stok kritis, perlu segera diisi ulang'
            ];
        }
    }

    public function getSafetyStockColor(): string
    {
        $status = $this->getSafetyStockStatus();
        return $status['color'];
    }

    /**
     * Check dan generate notifikasi stock menipis
     */
    public function checkAndGenerateNotification(): void
    {
        $currentStock = $this->stock;
        $safetyStock = $this->safety_stock;
        $barangName = $this->barang->nama_barang ?? 'Barang';

        // Skip jika safety stock belum diset
        if ($safetyStock <= 0) {
            return;
        }

        $notificationType = null;
        $title = '';
        $message = '';

        // Tentukan jenis notifikasi berdasarkan level stock
        if ($currentStock == 0) {
            $notificationType = 'stock_empty';
            $title = 'Stok Habis!';
            $message = "{$barangName} sudah habis dan perlu segera diisi ulang.";
        } elseif ($currentStock < ($safetyStock * 0.5)) {
            $notificationType = 'stock_critical';
            $title = 'Stok Kritis!';
            $message = "{$barangName} dalam kondisi kritis (Stok: {$currentStock}, Safety Stock: {$safetyStock}).";
        } elseif ($currentStock <= $safetyStock) {
            $notificationType = 'stock_low';
            $title = 'Stok Menipis';
            $message = "{$barangName} mendekati batas minimum (Stok: {$currentStock}, Safety Stock: {$safetyStock}).";
        }

        // Generate notifikasi jika diperlukan
        if ($notificationType) {
            // Cek apakah sudah ada notifikasi serupa yang belum dibaca dalam 24 jam terakhir
            $existingNotification = Notification::where('type', $notificationType)
                ->where('data->persediaan_id', $this->id)
                ->where('is_read', false)
                ->where('created_at', '>=', now()->subHours(24))
                ->first();

            // Hanya buat notifikasi baru jika belum ada yang serupa
            if (!$existingNotification) {
                Notification::create([
                    'type' => $notificationType,
                    'title' => $title,
                    'message' => $message,
                    'data' => [
                        'persediaan_id' => $this->id,
                        'barang_name' => $barangName,
                        'current_stock' => $currentStock,
                        'safety_stock' => $safetyStock,
                        'stock_level' => $this->getStockLevel()
                    ]
                ]);
            }
        }
    }

    /**
     * Get level stok untuk notifikasi
     */
    private function getStockLevel(): string
    {
        $currentStock = $this->stock;
        $safetyStock = $this->safety_stock;

        if ($currentStock == 0) {
            return 'empty';
        } elseif ($currentStock < ($safetyStock * 0.5)) {
            return 'critical';
        } elseif ($currentStock <= $safetyStock) {
            return 'low';
        } else {
            return 'normal';
        }
    }

    /**
     * Check notifikasi untuk semua persediaan
     */
    public static function checkAllStockNotifications(): void
    {
        $persediaanList = self::with('barang')->where('safety_stock', '>', 0)->get();
        
        foreach ($persediaanList as $persediaan) {
            $persediaan->checkAndGenerateNotification();
        }
    }
}
