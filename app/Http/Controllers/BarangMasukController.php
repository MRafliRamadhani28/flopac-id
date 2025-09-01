<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\BarangMasukDetail;
use App\Models\Persediaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    private const VALIDATION_RULES = [
        'tanggal_masuk' => 'required|date',
        'keterangan' => 'nullable|string|max:1000',
        'barang_id' => 'required|array|min:1',
        'barang_id.*' => 'required|exists:barangs,id',
        'qty' => 'required|array|min:1',
        'qty.*' => 'required|integer|min:1',
    ];

    private const VALIDATION_MESSAGES = [
        'tanggal_masuk.required' => 'Tanggal masuk harus diisi.',
        'tanggal_masuk.date' => 'Format tanggal masuk tidak valid.',
        'barang_id.required' => 'Minimal pilih satu barang.',
        'barang_id.array' => 'Data barang tidak valid.',
        'barang_id.min' => 'Minimal pilih satu barang.',
        'barang_id.*.required' => 'ID barang harus diisi.',
        'barang_id.*.exists' => 'Barang yang dipilih tidak ditemukan.',
        'qty.required' => 'Jumlah barang harus diisi.',
        'qty.array' => 'Data jumlah tidak valid.',
        'qty.min' => 'Minimal masukkan satu jumlah barang.',
        'qty.*.required' => 'Jumlah barang harus diisi.',
        'qty.*.integer' => 'Jumlah barang harus berupa angka.',
        'qty.*.min' => 'Jumlah barang minimal 1.',
    ];

    private const ERROR_MESSAGE_PREFIX = 'Terjadi kesalahan: ';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangMasuks = BarangMasuk::with(['creator', 'barangs'])->latest()->get();

        return view('barang_masuk.index', compact('barangMasuks'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load(['creator', 'details.barang']);

        return view('barang_masuk.show', compact('barangMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::with('persediaan')->get();
        // Preview nomor yang akan di-generate saat data disimpan
        $noBarangMasuk = \App\Models\DocumentCounter::previewNextNumber('barang_masuk', 'IN', 5);

        return view('barang_masuk.create', compact('barangs', 'noBarangMasuk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(self::VALIDATION_RULES, self::VALIDATION_MESSAGES);

        DB::beginTransaction();
        try {
            // Create BarangMasuk - nomor akan auto-generate oleh model event
            $barangMasuk = BarangMasuk::create([
                'tanggal_masuk' => $request->tanggal_masuk,
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id(),
            ]);

            // Create BarangMasukDetail and update stock persediaan
            foreach ($request->barang_id as $index => $barangId) {
                $qty = $request->qty[$index];
                
                BarangMasukDetail::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'barang_id' => $barangId,
                    'qty' => $qty,
                ]);
                
                // Update stock persediaan - tambah stock
                $this->updateStockPersediaan($barangId, $qty, 'tambah');
            }

            DB::commit();
            
            // Return JSON response for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Barang masuk berhasil ditambahkan.',
                    'data' => $barangMasuk->load('details.barang')
                ]);
            }
            
            return redirect()->route('barang_masuk.index')
                           ->with('success', 'Barang masuk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            
            // Return JSON error response for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => self::ERROR_MESSAGE_PREFIX . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', self::ERROR_MESSAGE_PREFIX . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, BarangMasuk $barangMasuk)
    {
        $response = null;
        DB::beginTransaction();
        try {
            // Restore stock persediaan before deleting
            foreach ($barangMasuk->details as $detail) {
                $this->updateStockPersediaan($detail->barang_id, $detail->qty, 'kurang');
            }
            
            // Delete detail records first
            $barangMasuk->details()->delete();
            
            // Then delete the main record
            $barangMasuk->delete();
            
            DB::commit();
            
            // Prepare response for AJAX or web
            if ($request->expectsJson()) {
                $response = response()->json([
                    'success' => true,
                    'message' => 'Barang masuk berhasil dihapus.'
                ]);
            } else {
                $response = redirect()->route('barang_masuk.index')
                           ->with('success', 'Barang masuk berhasil dihapus.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in destroy method: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                $response = response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            } else {
                $response = redirect()->back()
                           ->with('error', self::ERROR_MESSAGE_PREFIX . $e->getMessage());
            }
        }
        return $response;
    }

    /**
     * Update stock persediaan
     * 
     * @param int $barangId
     * @param int $qty
     * @param string $operasi ('tambah' atau 'kurang')
     */
    private function updateStockPersediaan($barangId, $qty, $operasi)
    {
        // Cari atau buat record persediaan
        $persediaan = Persediaan::firstOrCreate(
            ['barang_id' => $barangId],
            ['stock' => 0, 'safety_stock' => 0]
        );

        // Update stock berdasarkan operasi
        if ($operasi === 'tambah') {
            $persediaan->stock += $qty;
        } elseif ($operasi === 'kurang') {
            $persediaan->stock = max(0, $persediaan->stock - $qty); // Tidak boleh negatif
        }

        $persediaan->save();
    }
}
