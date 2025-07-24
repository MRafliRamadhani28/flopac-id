<?php

namespace App\Http\Controllers;

use App\Models\Persediaan;
use App\Models\Barang;
use Illuminate\Http\Request;

class PersediaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->ensurePersediaanForAllBarang();
        
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
        // Delete tidak diperbolehkan - data persediaan terikat dengan barang
        return redirect()->route('persediaan.index')
                        ->with('info', 'Data persediaan tidak dapat dihapus karena terikat dengan data barang.');
    }
}
