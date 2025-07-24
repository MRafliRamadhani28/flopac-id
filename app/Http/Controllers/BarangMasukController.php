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
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable|string|max:1000',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|integer|min:1',
        ]);

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
            return redirect()->route('barang_masuk.index')
                           ->with('success', 'Barang masuk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable|string|max:1000',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|integer|min:1',
        ]);

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
            return redirect()->route('barang_masuk.index')
                           ->with('success', 'Barang masuk berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $barangMasuk)
    {
        try {
            $barangMasuk->delete();
            return redirect()->route('barang_masuk.index')
                           ->with('success', 'Barang masuk berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
