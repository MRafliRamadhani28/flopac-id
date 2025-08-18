<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\Persediaan;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\PesananPersediaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        // Get all stock usage data with relations and group by date and item
        $rawData = PesananPersediaan::with([
            'pesanan', 
            'persediaan.barang'
        ])
        ->whereHas('pesanan')
        ->whereHas('persediaan.barang')
        ->where('jumlah_dipakai', '>', 0)
        ->get();

        // Group by date and barang, then sum quantities
        $groupedData = $rawData->groupBy(function($item) {
            return $item->pesanan->tanggal_pesanan->format('Y-m-d') . '_' . $item->persediaan->barang->id;
        })->map(function($group) {
            $first = $group->first();
            $totalStockUsed = $group->sum('jumlah_dipakai');
            $totalOrders = $group->count();
            
            // Get all pesanan IDs for this group
            $pesananIds = $group->pluck('pesanan.id')->unique()->implode(',');
            
            return (object) [
                'tanggal' => $first->pesanan->tanggal_pesanan,
                'barang' => $first->persediaan->barang,
                'total_stock_used' => $totalStockUsed,
                'total_orders' => $totalOrders,
                'pesanan_ids' => $pesananIds, // For detail view
                'raw_data' => $group // Keep raw data for detail view
            ];
        })->sortByDesc('tanggal')->values();

        return view('laporan.index', compact('groupedData'));
    }

    public function show($date_barang_id)
    {
        // Validate and decode the combined key (date_barangId)
        if (!str_contains($date_barang_id, '_')) {
            abort(404, 'Invalid parameter format');
        }
        
        $parts = explode('_', $date_barang_id);
        if (count($parts) < 2) {
            abort(404, 'Invalid parameter format');
        }
        
        $date = $parts[0];
        $barangId = $parts[1];
        
        // Validate date format
        try {
            $parsedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
        } catch (\Exception $e) {
            abort(404, 'Invalid date format');
        }
        
        // Validate barang ID is numeric
        if (!is_numeric($barangId)) {
            abort(404, 'Invalid barang ID');
        }
        
        // Get all usage records for this date and barang
        $usageRecords = PesananPersediaan::with([
            'pesanan.creator',
            'pesanan.processor',
            'persediaan.barang'
        ])
        ->whereHas('pesanan', function($query) use ($date) {
            $query->whereDate('tanggal_pesanan', $date);
        })
        ->whereHas('persediaan', function($query) use ($barangId) {
            $query->where('barang_id', $barangId);
        })
        ->where('jumlah_dipakai', '>', 0)
        ->orderBy('created_at', 'desc')
        ->get();

        if ($usageRecords->isEmpty()) {
            abort(404);
        }

        $barang = $usageRecords->first()->persediaan->barang;
        $tanggal = \Carbon\Carbon::parse($date);
        $totalStockUsed = $usageRecords->sum('jumlah_dipakai');
        $totalOrders = $usageRecords->count();

        return view('laporan.show', compact('usageRecords', 'barang', 'tanggal', 'totalStockUsed', 'totalOrders'));
    }

    public function exportPdf()
    {
        // Get all stock usage data with relations and group by date and item
        $rawData = PesananPersediaan::with([
            'pesanan', 
            'persediaan.barang'
        ])
        ->whereHas('pesanan')
        ->whereHas('persediaan.barang')
        ->where('jumlah_dipakai', '>', 0)
        ->get();

        // Group by date and barang, then sum quantities
        $groupedData = $rawData->groupBy(function($item) {
            return $item->pesanan->tanggal_pesanan->format('Y-m-d') . '_' . $item->persediaan->barang->id;
        })->map(function($group) {
            $first = $group->first();
            $totalStockUsed = $group->sum('jumlah_dipakai');
            $totalOrders = $group->count();
            
            return (object) [
                'tanggal' => $first->pesanan->tanggal_pesanan,
                'barang' => $first->persediaan->barang,
                'total_stock_used' => $totalStockUsed,
                'total_orders' => $totalOrders
            ];
        })->sortByDesc('tanggal')->values();

        $pdf = Pdf::loadView('laporan.pdf', compact('groupedData'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('laporan_pemakaian_stock_'.date('Ymd').'.pdf');
    }
}
