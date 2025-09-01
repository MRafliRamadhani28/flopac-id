<?php

namespace App\Http\Controllers;

use App\Models\PenyesuaianPersediaan;
use App\Models\PenyesuaianPersediaanDetail;
use App\Models\Barang;
use App\Models\Persediaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenyesuaianPersediaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penyesuaianPersediaans = PenyesuaianPersediaan::with(['creator', 'details.barang'])->latest()->get();

        return view('penyesuaian_persediaan.index', compact('penyesuaianPersediaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::with('persediaan')->get();
        // Preview nomor yang akan di-generate saat data disimpan
        $noPenyesuaian = \App\Models\DocumentCounter::previewNextNumber('penyesuaian_persediaan', 'ADJ', 5);

        return view('penyesuaian_persediaan.create', compact('barangs', 'noPenyesuaian'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_penyesuaian' => 'required|date',
            'keterangan' => 'nullable|string|max:1000',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
            'jenis' => 'required|array|min:1',
            'jenis.*' => 'required|in:penambahan,pengurangan',
        ]);

        DB::beginTransaction();
        try {
            // Create penyesuaian persediaan record
            $penyesuaianPersediaan = PenyesuaianPersediaan::create([
                'tanggal_penyesuaian' => $request->tanggal_penyesuaian,
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id(),
            ]);

            // Process each barang detail
            foreach ($request->barang_id as $index => $barangId) {
                $barang = Barang::findOrFail($barangId);
                
                // Cari atau buat record persediaan
                $persediaan = Persediaan::firstOrCreate(
                    ['barang_id' => $barangId],
                    ['stock' => 0, 'safety_stock' => 0]
                );

                $stockSebelum = $persediaan->stock;
                $stockPenyesuaian = $request->jumlah[$index];
                $jenisPenyesuaian = $request->jenis[$index];

                // Calculate new stock
                if ($jenisPenyesuaian === 'penambahan') {
                    $stockSesudah = $stockSebelum + $stockPenyesuaian;
                } else {
                    $stockSesudah = $stockSebelum - $stockPenyesuaian;
                    if ($stockSesudah < 0) {
                        throw new \Exception("Stock tidak boleh kurang dari 0 untuk barang {$barang->nama_barang}");
                    }
                }

                // Create detail record
                PenyesuaianPersediaanDetail::create([
                    'penyesuaian_persediaan_id' => $penyesuaianPersediaan->id,
                    'barang_id' => $barangId,
                    'stock_sebelum' => $stockSebelum,
                    'stock_penyesuaian' => $stockPenyesuaian,
                    'stock_sesudah' => $stockSesudah,
                    'jenis_penyesuaian' => $jenisPenyesuaian,
                ]);

                // Update persediaan stock
                $persediaan->update(['stock' => $stockSesudah]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Penyesuaian persediaan berhasil disimpan',
                    'data' => $penyesuaianPersediaan->load('details.barang')
                ]);
            }

            return redirect()->route('penyesuaian_persediaan.index')
                           ->with('success', 'Penyesuaian persediaan berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                           ->withInput()
                           ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penyesuaianPersediaan = PenyesuaianPersediaan::with(['creator', 'details.barang'])->findOrFail($id);

        return view('penyesuaian_persediaan.show', compact('penyesuaianPersediaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penyesuaianPersediaan = PenyesuaianPersediaan::with('details.barang')->findOrFail($id);
        $barangs = Barang::with('persediaan')->get();

        return view('penyesuaian_persediaan.show', compact('penyesuaianPersediaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal_penyesuaian' => 'required|date',
            'keterangan' => 'nullable|string|max:1000',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',
            'stock_penyesuaian' => 'required|array|min:1',
            'stock_penyesuaian.*' => 'required|integer',
            'jenis_penyesuaian' => 'required|array|min:1',
            'jenis_penyesuaian.*' => 'required|in:penambahan,pengurangan',
        ]);

        DB::beginTransaction();
        try {
            $penyesuaianPersediaan = PenyesuaianPersediaan::findOrFail($id);

            // Revert previous stock changes
            foreach ($penyesuaianPersediaan->details as $detail) {
                $persediaan = $detail->barang->persediaan;
                if ($persediaan) {
                    $persediaan->update(['stock' => $detail->stock_sebelum]);
                }
            }

            // Delete old details
            $penyesuaianPersediaan->details()->delete();

            // Update main record
            $penyesuaianPersediaan->update([
                'tanggal_penyesuaian' => $request->tanggal_penyesuaian,
                'keterangan' => $request->keterangan,
            ]);

            // Process new details (same logic as store)
            foreach ($request->barang_id as $index => $barangId) {
                $barang = Barang::findOrFail($barangId);
                $persediaan = $barang->persediaan;
                
                if (!$persediaan) {
                    throw new \Exception("Persediaan untuk barang {$barang->nama_barang} tidak ditemukan");
                }

                $stockSebelum = $persediaan->stock;
                $stockPenyesuaian = $request->stock_penyesuaian[$index];
                $jenisPenyesuaian = $request->jenis_penyesuaian[$index];

                if ($jenisPenyesuaian === 'penambahan') {
                    $stockSesudah = $stockSebelum + $stockPenyesuaian;
                } else {
                    $stockSesudah = $stockSebelum - $stockPenyesuaian;
                    if ($stockSesudah < 0) {
                        throw new \Exception("Stock tidak boleh kurang dari 0 untuk barang {$barang->nama_barang}");
                    }
                }

                PenyesuaianPersediaanDetail::create([
                    'penyesuaian_persediaan_id' => $penyesuaianPersediaan->id,
                    'barang_id' => $barangId,
                    'stock_sebelum' => $stockSebelum,
                    'stock_penyesuaian' => $stockPenyesuaian,
                    'stock_sesudah' => $stockSesudah,
                    'jenis_penyesuaian' => $jenisPenyesuaian,
                ]);

                $persediaan->update(['stock' => $stockSesudah]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Penyesuaian persediaan berhasil diperbarui',
                    'data' => $penyesuaianPersediaan->load('details.barang')
                ]);
            }

            return redirect()->route('penyesuaian_persediaan.index')
                           ->with('success', 'Penyesuaian persediaan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                           ->withInput()
                           ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $penyesuaianPersediaan = PenyesuaianPersediaan::findOrFail($id);

            // Revert stock changes
            foreach ($penyesuaianPersediaan->details as $detail) {
                $persediaan = $detail->barang->persediaan;
                if ($persediaan) {
                    $persediaan->update(['stock' => $detail->stock_sebelum]);
                }
            }

            $penyesuaianPersediaan->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Penyesuaian persediaan berhasil dihapus'
                ]);
            }

            return redirect()->route('penyesuaian_persediaan.index')
                           ->with('success', 'Penyesuaian persediaan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                           ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
