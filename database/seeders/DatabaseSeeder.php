<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            BarangSeeder::class,
        ]);

        // Create owner user
        $owner = User::factory()->create([
            'name' => 'Owner',
            'email' => 'owner@flopac.id',
            'username' => 'owner',
            'password' => bcrypt('password'),
        ]);
        $owner->assignRole('Owner');

        // Create persediaan user
        $persediaan = User::factory()->create([
            'name' => 'Staff Persediaan',
            'email' => 'persediaan@flopac.id',
            'username' => 'persediaan',
            'password' => bcrypt('password'),
        ]);
        $persediaan->assignRole('Persediaan');

        // Create produksi user
        $produksi = User::factory()->create([
            'name' => 'Staff Produksi',
            'email' => 'produksi@flopac.id',
            'username' => 'produksi',
            'password' => bcrypt('password'),
        ]);
        $produksi->assignRole('Produksi');
    }
}
