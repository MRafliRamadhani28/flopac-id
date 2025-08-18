<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DocumentCounter;
use App\Models\PesananPersediaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    private const VALIDATION_RULES = [
        'nama_pelanggan' => 'required|string|max:255',
        'alamat' => 'required|string|max:1000',
        'model' => 'required|string|max:255',
        'sumber' => 'required|in:Shopee,Tiktok,Instagram,WhatsApp,Offline',
        'catatan' => 'nullable|string|max:1000',
        'tanggal_pesanan' => 'required|date',
        'tenggat_pesanan' => 'required|date|after_or_equal:tanggal_pesanan',
    ];

    private const VALIDATION_MESSAGES = [
        'nama_pelanggan.required' => 'Nama pelanggan harus diisi.',
        'alamat.required' => 'Alamat harus diisi.',
        'model.required' => 'Model harus diisi.',
        'sumber.required' => 'Sumber harus diisi.',
        'sumber.in' => 'Sumber harus dipilih dari pilihan yang tersedia.',
        'tanggal_pesanan.required' => 'Tanggal pesanan harus diisi.',
        'tenggat_pesanan.required' => 'Tenggat pesanan harus diisi.',
        'tenggat_pesanan.after_or_equal' => 'Tenggat pesanan tidak boleh lebih awal dari tanggal pesanan.',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanans = Pesanan::with(['creator', 'processor'])->latest()->get();
        
        return view('pesanan.index', compact('pesanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Preview nomor yang akan di-generate saat data disimpan
        $noPesanan = DocumentCounter::previewNextNumber('pesanan', 'PSN', 5);
        
        return view('pesanan.create', compact('noPesanan'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['creator', 'processor', 'persediaanUsage.persediaan.barang']);
        
        return view('pesanan.show', compact('pesanan'));
    }

    /**
     * Get pesanan data for edit modal (AJAX)
     */
    public function getEditData(Pesanan $pesanan)
    {
        $pesanan->load(['creator', 'processor']);
        
        return response()->json([
            'id' => $pesanan->id,
            'no_pesanan' => $pesanan->no_pesanan,
            'nama_pelanggan' => $pesanan->nama_pelanggan,
            'alamat' => $pesanan->alamat,
            'model' => $pesanan->model,
            'sumber' => $pesanan->sumber,
            'catatan' => $pesanan->catatan,
            'tanggal_pesanan' => $pesanan->tanggal_pesanan->format('Y-m-d'),
            'tenggat_pesanan' => $pesanan->tenggat_pesanan->format('Y-m-d'),
            'status' => $pesanan->status,
            'is_overdue' => $pesanan->is_overdue,
            'creator_name' => $pesanan->creator ? $pesanan->creator->name : '-',
            'processor_name' => $pesanan->processor ? $pesanan->processor->name : null,
            'created_at' => $pesanan->created_at->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(self::VALIDATION_RULES, self::VALIDATION_MESSAGES);

        DB::beginTransaction();
        try {
            // Create Pesanan - nomor akan auto-generate oleh model event
            $pesanan = Pesanan::create([
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat' => $request->alamat,
                'model' => $request->model,
                'sumber' => $request->sumber,
                'catatan' => $request->catatan,
                'tanggal_pesanan' => $request->tanggal_pesanan,
                'tenggat_pesanan' => $request->tenggat_pesanan,
                'created_by' => Auth::id(),
            ]);

            DB::commit();
            
            // Return JSON response for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil ditambahkan.',
                    'data' => $pesanan->load('creator', 'processor')
                ]);
            }
            
            return redirect()->route('pesanan.index')
                           ->with('success', 'Pesanan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            
            // Return JSON error response for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pesanan $pesanan)
    {
        try {
            $request->validate(self::VALIDATION_RULES, self::VALIDATION_MESSAGES);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        DB::beginTransaction();
        try {
            $pesanan->update([
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat' => $request->alamat,
                'model' => $request->model,
                'sumber' => $request->sumber,
                'catatan' => $request->catatan,
                'tanggal_pesanan' => $request->tanggal_pesanan,
                'tenggat_pesanan' => $request->tenggat_pesanan,
            ]);

            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil diupdate.',
                    'data' => $pesanan->load('creator', 'processor')
                ]);
            }
            
            return redirect()->route('pesanan.index')
                           ->with('success', 'Pesanan berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

        /**
     * Get next pesanan number for preview
     */
    public function getNextNumber()
    {
        $nextNumber = DocumentCounter::previewNextNumber('pesanan', 'PSN');
        
        return response()->json([
            'next_number' => $nextNumber
        ]);
    }

    /**
     * Update pesanan status
     */
    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status' => 'required|in:Pending,Diproses,Selesai'
        ]);

        DB::beginTransaction();
        try {
            $updateData = ['status' => $request->status];
            
            // Set diproses_oleh ketika status berubah ke Diproses
            if ($request->status === 'Diproses' && $pesanan->status !== 'Diproses') {
                $updateData['diproses_oleh'] = Auth::id();
            }
            
            $pesanan->update($updateData);
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status pesanan berhasil diupdate.',
                    'data' => $pesanan->load('creator', 'processor')
                ]);
            }
            
            return redirect()->back()
                           ->with('success', 'Status pesanan berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process stock reduction and update pesanan status to Diproses
     */
    public function processStock(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'persediaan_ids' => 'required|array',
            'persediaan_ids.*' => 'exists:persediaan,id',
            'jumlah_dipakai' => 'required|array',
            'jumlah_dipakai.*' => 'integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $pesanan = Pesanan::findOrFail($request->pesanan_id);
            
            // Validate pesanan status
            if ($pesanan->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan sudah diproses sebelumnya.'
                ], 422);
            }

            $persediaanIds = $request->persediaan_ids;
            $jumlahDipakai = $request->jumlah_dipakai;
            
            // Validate stock availability and process each item
            for ($i = 0; $i < count($persediaanIds); $i++) {
                $persediaanId = $persediaanIds[$i];
                // Handle empty value: if empty or null, set to 0
                $jumlah = intval($jumlahDipakai[$i] ?? 0);
                
                if ($jumlah <= 0) {
                    continue; // Skip if no stock to reduce
                }
                
                $persediaan = \App\Models\Persediaan::findOrFail($persediaanId);
                
                // Validate stock availability
                if ($jumlah > $persediaan->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok tidak mencukupi untuk barang: {$persediaan->barang->nama_barang}. Tersedia: {$persediaan->stock}, Diminta: {$jumlah}"
                    ], 422);
                }
                
                // Update both dipakai column and reduce stock
                $persediaan->dipakai = ($persediaan->dipakai ?? 0) + $jumlah;
                $persediaan->stock = $persediaan->stock - $jumlah;
                $persediaan->save();
                
                // Save to pivot table for tracking
                PesananPersediaan::create([
                    'pesanan_id' => $pesanan->id,
                    'persediaan_id' => $persediaanId,
                    'jumlah_dipakai' => $jumlah,
                ]);
            }
            
            // Update pesanan status to Diproses
            $pesanan->update([
                'status' => 'Diproses',
                'diproses_oleh' => Auth::id()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil dikurangi dan status pesanan diupdate ke Diproses.',
                'data' => $pesanan->load('creator', 'processor')
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in processStock method: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Pesanan $pesanan)
    {
        DB::beginTransaction();
        try {
            // First, restore stock for all persediaan used in this pesanan
            $usageRecords = PesananPersediaan::where('pesanan_id', $pesanan->id)->get();
            
            foreach ($usageRecords as $usage) {
                $persediaan = \App\Models\Persediaan::find($usage->persediaan_id);
                if ($persediaan) {
                    // Restore stock
                    $persediaan->stock = $persediaan->stock + $usage->jumlah_dipakai;
                    // Reduce dipakai counter
                    $persediaan->dipakai = max(0, ($persediaan->dipakai ?? 0) - $usage->jumlah_dipakai);
                    $persediaan->save();
                }
            }
            
            // Delete usage records
            PesananPersediaan::where('pesanan_id', $pesanan->id)->delete();
            
            // Delete the pesanan
            $pesanan->delete();
            
            DB::commit();
            
            // Prepare response for AJAX or web
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dihapus dan stok telah dikembalikan.'
                ]);
            }
            
            return redirect()->route('pesanan.index')
                           ->with('success', 'Pesanan berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in destroy method: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get production usage data for completing pesanan
     */
    public function getProductionUsageData(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id'
        ]);

        try {
            $pesananId = $request->pesanan_id;
            
            // Get usage records for this pesanan
            $usageRecords = PesananPersediaan::with(['persediaan.barang'])
                ->where('pesanan_id', $pesananId)
                ->get()
                ->map(function ($usage) {
                    return [
                        'id' => $usage->id,
                        'persediaan_id' => $usage->persediaan_id,
                        'nama_barang' => $usage->persediaan->barang->nama_barang ?? 'Barang Tidak Ditemukan',
                        'warna' => $usage->persediaan->barang->warna ?? '-',
                        'satuan' => $usage->persediaan->barang->satuan ?? '-',
                        'jumlah_dipakai' => $usage->jumlah_dipakai,
                        'persediaan' => $usage->persediaan
                    ];
                });

            return response()->json($usageRecords);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data pemakaian produksi',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete production and update pesanan status to Selesai
     */
    public function completeProduction(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'persediaan_ids' => 'required|array',
            'persediaan_ids.*' => 'exists:persediaan,id',
            'jumlah_produksi' => 'required|array',
            'jumlah_produksi.*' => 'integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $pesanan = Pesanan::findOrFail($request->pesanan_id);
            
            // Validate pesanan status
            if ($pesanan->status !== 'Diproses') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan harus dalam status "Diproses" untuk dapat diselesaikan.'
                ], 422);
            }

            $persediaanIds = $request->persediaan_ids;
            $jumlahProduksi = $request->jumlah_produksi;
            
            // Process each production usage
            for ($i = 0; $i < count($persediaanIds); $i++) {
                $persediaanId = $persediaanIds[$i];
                // Handle empty value: if empty or null, set to 0
                $jumlah = intval($jumlahProduksi[$i] ?? 0);
                
                if ($jumlah < 0) {
                    continue; // Skip negative values
                }
                
                $persediaan = \App\Models\Persediaan::findOrFail($persediaanId);
                
                // Get usage record for this pesanan and persediaan
                $usageRecord = PesananPersediaan::where('pesanan_id', $pesanan->id)
                    ->where('persediaan_id', $persediaanId)
                    ->first();
                
                if (!$usageRecord) {
                    continue; // Skip if no usage record found
                }
                
                // Validate production usage doesn't exceed used stock
                if ($jumlah > $usageRecord->jumlah_dipakai) {
                    return response()->json([
                        'success' => false,
                        'message' => "Pemakaian produksi tidak boleh melebihi stok yang dipakai untuk barang: {$persediaan->barang->nama_barang}. Dipakai: {$usageRecord->jumlah_dipakai}, Diminta: {$jumlah}"
                    ], 422);
                }
                
                // Calculate returned stock
                $sisaDikembalikan = $usageRecord->jumlah_dipakai - $jumlah;
                
                // Update persediaan: reduce dipakai and increase stock with returned amount
                $persediaan->dipakai = 0;
                $persediaan->stock = $persediaan->stock + $sisaDikembalikan;
                $persediaan->save();
                
                // Update usage record with production amount
                $usageRecord->update([
                    'jumlah_dipakai' => $jumlah // Now this represents actual production usage
                ]);
            }
            
            // Update pesanan status to Selesai
            $pesanan->update([
                'status' => 'Selesai'
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diselesaikan dan stok yang tidak terpakai telah dikembalikan.',
                'data' => $pesanan->load('creator', 'processor')
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in completeProduction method: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
