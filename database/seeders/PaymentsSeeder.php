<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sales = Sale::all();

        foreach ($sales as $sale) {
            // HANYA sales dengan status selain "Belum Dibayar" yang dapat payment
            if ($sale->status === 'Belum Dibayar') {
                continue; // Skip - memang tidak ada pembayaran
            }

            // WAJIB buat payment untuk status lainnya
            $tanggalBayar = Carbon::parse($sale->tanggal_penjualan)->addDays(rand(1, 5));

            // Tentukan jumlah bayar berdasarkan status
            if ($sale->status === 'Belum Dibayar Sepenuhnya') {
                // Bayar 50-80% dari total (pastikan tidak 100%)
                $persentase = 100;
                $jumlahBayar = (int)($sale->total_harga * $persentase);
            } else { // 'Sudah Dibayar'
                // Bayar 100% dari total
                $jumlahBayar = $sale->total_harga;
            }

            // PASTIKAN setiap sale yang butuh payment dapat payment
            Payment::create([
                'kode_pembayaran' => 'PAY-' . $tanggalBayar->format('YmdHis') . '-' . $sale->id,
                'sale_id'         => $sale->id,
                'tanggal_bayar'   => $tanggalBayar,
                'jumlah'          => $jumlahBayar,
                'catatan'         => 'Payment for ' . $sale->kode_penjualan,
            ]);
        }
    }
}
