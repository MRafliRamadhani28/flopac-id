<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Barang;
use App\Models\Persediaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get order statistics
        $pesananBelumProses = Pesanan::where('status', 'Pending')->count();
        $pesananSedangProses = Pesanan::where('status', 'Diproses')->count();
        $totalPesanan = Pesanan::count();

        // Get inventory statistics
        $totalBarang = Barang::count();
        $barangMenipis = Persediaan::whereColumn('stock', '<=', 'safety_stock')->count();

        $chartData = [];
        for ($month = 1; $month <= 12; $month++) {
            $count = Pesanan::whereYear('tanggal_pesanan', date('Y'))
                ->whereMonth('tanggal_pesanan', $month)
                ->count();
            $chartData[] = $count;
        }

        return view('dashboard', compact(
            'pesananBelumProses',
            'pesananSedangProses', 
            'totalPesanan',
            'totalBarang',
            'barangMenipis',
            'chartData'
        ));
    }
}
