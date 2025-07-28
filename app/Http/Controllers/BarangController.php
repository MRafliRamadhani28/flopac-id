<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::all();
        return view('barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'warna' => 'nullable|string|max:50',
            'satuan' => 'required|string|max:20',
        ]);

        $barang = Barang::create($request->all());

        // Return JSON response for AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditambahkan.',
                'data' => $barang
            ]);
        }

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'warna' => 'nullable|string|max:50',
            'satuan' => 'required|string|max:20',
        ]);

        $barang->update($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil diperbarui.',
                'data' => $barang
            ]);
        }

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Barang $barang)
    {
        try {
            // Check if barang can be safely deleted
            if (!$barang->canBeDeleted()) {
                $usageDetails = $barang->getUsageDetails();
                $message = 'Barang tidak dapat dihapus karena sudah digunakan dalam:';
                foreach ($usageDetails as $detail) {
                    $message .= "\n {$detail}";
                }
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->route('barang.index')
                               ->with('error', $message);
            }
            
            // Delete related persediaan first if exists
            if ($barang->persediaan) {
                $barang->persediaan->delete();
            }
            
            // Then delete the barang
            $barang->delete();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Barang berhasil dihapus.'
                ]);
            }
            
            return redirect()->route('barang.index')
                           ->with('success', 'Barang berhasil dihapus.');
                           
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus barang: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('barang.index')
                           ->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }
}
