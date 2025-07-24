<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            [
                'nama_barang' => 'Baju Kaos Polos',
                'warna' => 'Merah',
                'satuan' => 'pcs',
            ],
            [
                'nama_barang' => 'Celana Jeans',
                'warna' => 'Biru',
                'satuan' => 'pcs',
            ],
            [
                'nama_barang' => 'Topi Baseball',
                'warna' => 'Hitam',
                'satuan' => 'pcs',
            ],
            [
                'nama_barang' => 'Kain Katun',
                'warna' => 'Putih',
                'satuan' => 'meter',
            ],
            [
                'nama_barang' => 'Benang Jahit',
                'warna' => null,
                'satuan' => 'gram',
            ]
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
