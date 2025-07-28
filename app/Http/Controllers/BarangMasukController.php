<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\BarangMasukDetail;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::all();
        $noBarangMasuk = BarangMasuk::generateNoBarangMasuk();

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
            // Create BarangMasuk
            $barangMasuk = BarangMasuk::create([
                'no_barang_masuk' => BarangMasuk::generateNoBarangMasuk(),
                'tanggal_masuk' => $request->tanggal_masuk,
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id(),
            ]);

            // Create BarangMasukDetail
            foreach ($request->barang_id as $index => $barangId) {
                BarangMasukDetail::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'barang_id' => $barangId,
                    'qty' => $request->qty[$index],
                ]);
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
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $barangMasuk)
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        $barangMasuk->load('details.barang');
        
        return view('barang_masuk.edit', compact('barangMasuk', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $request->validate(self::VALIDATION_RULES, self::VALIDATION_MESSAGES);

        $response = null;
        DB::beginTransaction();
        try {
            // Update BarangMasuk
            $barangMasuk->update([
                'tanggal_masuk' => $request->tanggal_masuk,
                'keterangan' => $request->keterangan,
            ]);

            // Delete existing details
            $barangMasuk->details()->delete();

            // Create new details
            foreach ($request->barang_id as $index => $barangId) {
                BarangMasukDetail::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'barang_id' => $barangId,
                    'qty' => $request->qty[$index],
                ]);
            }

            DB::commit();
            
            if ($request->expectsJson()) {
                $response = response()->json([
                    'success' => true,
                    'message' => 'Barang masuk berhasil diupdate.',
                    'data' => $barangMasuk->load('details.barang')
                ]);
            } else {
                $response = redirect()->route('barang_masuk.index')
                               ->with('success', 'Barang masuk berhasil diupdate.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                $response = response()->json([
                    'success' => false,
                    'message' => self::ERROR_MESSAGE_PREFIX . $e->getMessage()
                ], 422);
            } else {
                $response = redirect()->back()
                               ->withInput()
                               ->with('error', self::ERROR_MESSAGE_PREFIX . $e->getMessage());
            }
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, BarangMasuk $barangMasuk)
    {
        $response = null;
        DB::beginTransaction();
        try {
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
}
