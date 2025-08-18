<?php

namespace App\Http\Controllers;

use App\Models\Persediaan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PersediaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $this->ensurePersediaanForAllBarang();
        
        // Auto update safety stock untuk persediaan yang belum ada atau sudah lama
        $this->autoUpdateSafetyStock();
        
        // Check dan generate notifikasi stock menipis
        $this->checkStockNotifications();
        
        $persediaan = Persediaan::with('barang')->get();
        return view('persediaan.index', compact('persediaan'));
    }

    /**
     * Pastikan semua barang memiliki data persediaan
     */
    private function ensurePersediaanForAllBarang()
    {
        $barangs = Barang::whereDoesntHave('persediaan')->get();
        
        foreach ($barangs as $barang) {
            Persediaan::create([
                'barang_id' => $barang->id,
                'safety_stock' => 0,
                'stock' => 0
            ]);
        }
    }

    /**
     * Auto update safety stock untuk persediaan yang memerlukan update
     */
    private function autoUpdateSafetyStock()
    {
        // Update safety stock yang belum pernah dihitung (masih 0) atau sudah lama (>7 hari)
        $persediaanList = Persediaan::where(function($query) {
            $query->where('safety_stock', 0)
                  ->orWhere('updated_at', '<', now()->subDays(7));
        })->limit(10)->get(); // Batasi 10 item per request untuk performa

        foreach ($persediaanList as $persediaan) {
            $persediaan->updateSafetyStock();
        }
    }

    /**
     * Check dan generate notifikasi stock menipis
     */
    private function checkStockNotifications()
    {
        Persediaan::checkAllStockNotifications();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Redirect ke index karena tidak ada form create (auto-created)
        return redirect()->route('persediaan.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Redirect ke index karena tidak ada store manual (auto-created)
        return redirect()->route('persediaan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $persediaan = Persediaan::with('barang')->findOrFail($id);
        return view('persediaan.show', compact('persediaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Edit tidak diperbolehkan - hanya tampil data
        return redirect()->route('persediaan.index')
                        ->with('info', 'Data persediaan hanya dapat dilihat, tidak dapat diedit.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update tidak diperbolehkan
        return redirect()->route('persediaan.index')
                        ->with('info', 'Data persediaan hanya dapat dilihat, tidak dapat diedit.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('persediaan.index')
                        ->with('info', 'Data persediaan tidak dapat dihapus karena terikat dengan data barang.');
    }

    /**
     * Get stock data for pesanan processing
     */
    public function getStockData()
    {
        try {
            $persediaan = Persediaan::with('barang')
                ->whereHas('barang')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama_barang' => $item->barang->nama_barang ?? 'Barang Tidak Ditemukan',
                        'warna' => $item->barang->warna ?? '-',
                        'satuan' => $item->barang->satuan ?? '-',
                        'stock' => $item->stock ?? 0,
                        'dipakai' => $item->dipakai ?? 0,
                        'stok_tersedia' => $item->stock ?? 0,
                    ];
                })
                ->filter(function ($item) {
                    return $item['stok_tersedia'] > 0;
                })
                ->values();

            return response()->json($persediaan);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data stok',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function exportStokMenipis()
    {
        $data = Persediaan::with('barang')
            ->where('safety_stock', '!=', 0)
            ->whereColumn('stock', '<=', 'safety_stock')
            ->orderBy('stock', 'asc')
            ->get();

        $pdf = Pdf::loadView('persediaan.stok_menipis_pdf', compact('data'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan_stok_menipis_' . date('Ymd') . '.pdf');
    }
}
