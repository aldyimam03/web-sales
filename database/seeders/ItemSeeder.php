<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            Item::create([
                'kode'   => 'ITM-' . strtoupper(Str::random(6)), 
                'nama'   => "Item $i",
                'harga'  => rand(10000, 500000),
                'gambar' => "https://picsum.photos/seed/picsum/400/400",
            ]);
        }
    }
}
