<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Persediaan;

class CalculateSafetyStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'safety-stock:calculate {--id= : ID persediaan tertentu} {--force : Force update meskipun tidak ada perubahan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghitung safety stock untuk semua barang atau barang tertentu berdasarkan rumus K × σD × √LT';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai perhitungan Safety Stock...');
        
        $persediaanId = $this->option('id');
        $force = $this->option('force');
        
        if ($persediaanId) {
            $this->calculateSingleItem($persediaanId, $force);
        } else {
            $this->calculateAllItems($force);
        }
        
        $this->info('Perhitungan Safety Stock selesai!');
    }
    
    /**
     * Calculate safety stock for single item
     */
    private function calculateSingleItem($id, $force = false)
    {
        $persediaan = Persediaan::with('barang')->find($id);
        
        if (!$persediaan) {
            $this->error("Persediaan dengan ID {$id} tidak ditemukan!");
            return;
        }
        
        $this->info("Menghitung safety stock untuk: {$persediaan->barang->nama_barang}");
        
        $calculation = $persediaan->calculateSafetyStock();
        
        if ($calculation['success']) {
            $oldSafetyStock = $persediaan->safety_stock;
            $newSafetyStock = $calculation['safety_stock'];
            
            if ($oldSafetyStock != $newSafetyStock || $force) {
                $persediaan->safety_stock = $newSafetyStock;
                $persediaan->save();
                
                $this->line("  ✅ Updated: {$oldSafetyStock} → {$newSafetyStock}");
                $this->line("  📊 Parameters: K={$calculation['parameters']['K']}, σD={$calculation['parameters']['standard_deviation']}, LT={$calculation['parameters']['LT']}");
                $this->line("  📈 Data points: {$calculation['parameters']['data_points']} hari");
            } else {
                $this->line("  ⏭️  Tidak ada perubahan: {$oldSafetyStock}");
            }
        } else {
            $this->warn("  ⚠️  {$calculation['message']}");
        }
    }
    
    /**
     * Calculate safety stock for all items
     */
    private function calculateAllItems($force = false)
    {
        $persediaanList = Persediaan::with('barang')->get();
        $progressBar = $this->output->createProgressBar($persediaanList->count());
        
        $updated = 0;
        $skipped = 0;
        $errors = 0;
        
        foreach ($persediaanList as $persediaan) {
            $calculation = $persediaan->calculateSafetyStock();
            
            if ($calculation['success']) {
                $oldSafetyStock = $persediaan->safety_stock;
                $newSafetyStock = $calculation['safety_stock'];
                
                if ($oldSafetyStock != $newSafetyStock || $force) {
                    $persediaan->safety_stock = $newSafetyStock;
                    $persediaan->save();
                    $updated++;
                } else {
                    $skipped++;
                }
            } else {
                $errors++;
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        $this->table(['Status', 'Jumlah'], [
            ['Updated', $updated],
            ['Skipped (no change)', $skipped], 
            ['Errors (no data)', $errors],
            ['Total', $persediaanList->count()]
        ]);
    }
}
