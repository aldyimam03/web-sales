<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Sale;
use App\Models\User;
use App\Models\SaleItem;
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Setiap user pasti punya 3 sales
            for ($saleIndex = 1; $saleIndex <= 3; $saleIndex++) {
                $tanggal = Carbon::create(
                    now()->year,
                    rand(1, 12),
                    rand(1, 28),
                    rand(0, 23),
                    rand(0, 59),
                    rand(0, 59)
                );

                $kodeSale = 'SALE-' . $tanggal->format('YmdHis') . '-' . $user->id . '-' . $saleIndex;

                // Random status untuk variasi
                $statuses = ['Belum Dibayar', 'Belum Dibayar Sepenuhnya', 'Sudah Dibayar'];
                $status = $statuses[array_rand($statuses)];

                $sale = Sale::create([
                    'kode_penjualan'    => $kodeSale,
                    'tanggal_penjualan' => $tanggal,
                    'status'            => $status,
                    'total_harga'       => 0,
                    'user_id'           => $user->id // Pastikan user ini
                ]);

                // Buat items untuk sale ini
                $items = Item::inRandomOrder()->take(rand(1, 3))->get();
                $totalHarga = 0;

                foreach ($items as $item) {
                    $qty = rand(1, 3);
                    $harga = $item->harga;
                    $subtotal = $harga * $qty;

                    SaleItem::create([
                        'sale_id'    => $sale->id,
                        'items_id'   => $item->id,
                        'quantity'   => $qty,
                        'harga'      => $harga,
                        'total_harga' => $subtotal,
                    ]);

                    $totalHarga += $subtotal;
                }

                $sale->update(['total_harga' => $totalHarga]);
            }
        }
    }
}
