# Safety Stock Calculator

## Overview
Sistem perhitungan Safety Stock menggunakan rumus: **Safety Stock = K × σD × √LT**

Dimana:
- **K** = Service Level Factor (Z-score)
- **σD** = Standard Deviasi dari demand harian
- **LT** = Lead Time (dalam hari)

## Konfigurasi Environment

Tambahkan konfigurasi berikut di file `.env`:

```env
# Safety Stock Configuration
SAFETY_STOCK_K=1.65          # Service level 95% = 1.65, 99% = 2.33
SAFETY_STOCK_LT=7            # Lead time dalam hari
SAFETY_STOCK_ANALYSIS_DAYS=90 # Periode analisis data historis (hari)
```

### Service Level Reference:
- 90% = 1.28
- 95% = 1.65
- 99% = 2.33

## Penggunaan

### 1. Melalui Model (Programmatically)

```php
use App\Models\Persediaan;

// Mendapatkan persediaan
$persediaan = Persediaan::find(1);

// Menghitung safety stock
$calculation = $persediaan->calculateSafetyStock();

if ($calculation['success']) {
    echo "Safety Stock: " . $calculation['safety_stock'];
    echo "Standard Deviation: " . $calculation['parameters']['standard_deviation'];
    echo "Mean Demand: " . $calculation['parameters']['mean_demand'];
} else {
    echo "Error: " . $calculation['message'];
}

// Update safety stock otomatis
$persediaan->updateSafetyStock();

// Mendapatkan status stok
$status = $persediaan->getSafetyStockStatus();
echo $status['message']; // "Stok aman", "Stok kritis", dll
```

### 2. Melalui Artisan Command

```bash
# Hitung safety stock untuk semua barang
php artisan safety-stock:calculate

# Hitung safety stock untuk barang tertentu
php artisan safety-stock:calculate --id=1

# Force update meskipun tidak ada perubahan
php artisan safety-stock:calculate --force

# Kombinasi
php artisan safety-stock:calculate --id=1 --force
```

## Response Format

### calculateSafetyStock()

Sukses:
```php
[
    'success' => true,
    'safety_stock' => 25,
    'parameters' => [
        'K' => 1.65,
        'standard_deviation' => 3.2,
        'LT' => 7,
        'mean_demand' => 12.5,
        'analysis_days' => 90,
        'data_points' => 45
    ],
    'raw_usage_data' => [10, 15, 8, ...]
]
```

Error:
```php
[
    'success' => false,
    'message' => 'Tidak ada data penggunaan dalam 90 hari terakhir',
    'safety_stock' => 0,
    'parameters' => [...]
]
```

### getSafetyStockStatus()

```php
[
    'status' => 'safe',        // 'safe', 'warning', 'critical'
    'color' => 'success',      // 'success', 'warning', 'danger'
    'message' => 'Stok aman'   // Pesan untuk UI
]
```

## Logika Perhitungan

1. **Data Collection**: Mengambil data penggunaan barang dari tabel `pesanan_persediaan` dalam periode tertentu
2. **Filtering**: Hanya menggunakan pesanan dengan status 'Selesai' atau 'Dalam Proses'
3. **Grouping**: Mengelompokkan data per hari dan menjumlahkan penggunaan harian
4. **Statistical Analysis**: 
   - Menghitung rata-rata demand harian (μD)
   - Menghitung standard deviasi (σD)
5. **Safety Stock Calculation**: K × σD × √LT

## Integrasi dengan UI

Method yang tersedia untuk UI:
- `getSafetyStockColor()`: Mendapatkan warna Bootstrap untuk status
- `getSafetyStockStatus()`: Mendapatkan detail status lengkap
- `updateSafetyStock()`: Update safety stock dengan perhitungan baru

## Automation

Untuk perhitungan otomatis secara periodik, tambahkan ke scheduler Laravel:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Hitung ulang safety stock setiap hari jam 2 pagi
    $schedule->command('safety-stock:calculate')->dailyAt('02:00');
}
```

## Troubleshooting

### Tidak Ada Data
Jika mendapat pesan "Tidak ada data penggunaan", pastikan:
1. Ada pesanan dengan status 'Selesai' atau 'Dalam Proses'
2. Pesanan memiliki tanggal dalam periode analisis
3. Barang sudah pernah digunakan dalam pesanan

### Nilai Safety Stock Sangat Tinggi
Jika nilai terlalu tinggi:
1. Kurangi nilai K (service level)
2. Kurangi lead time (LT)
3. Periksa data outlier dalam periode analisis

### Nilai Safety Stock Sangat Rendah
Jika nilai terlalu rendah:
1. Tingkatkan nilai K (service level)
2. Tingkatkan lead time (LT)
3. Periksa apakah ada cukup data historis
