<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with('sale')->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_bayar', [$request->start_date, $request->end_date]);
        }

        $payments = $query->paginate(10);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sales = Sale::whereIn('status', ['Belum Dibayar', 'Belum Dibayar Sepenuhnya'])->get();
        return view('payments.create', compact('sales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required|exists:sales,id',
                'tanggal_bayar' => 'required|date',
                'jumlah' => 'required|numeric|min:1',
                'catatan' => 'nullable|string',
            ]);

            DB::transaction(function () use ($request) {
                $sale = Sale::findOrFail($request->sale_id);

                // Hitung total sudah dibayar (dari semua payment untuk sale ini)
                $totalSudahDibayar = $sale->payments()->sum('jumlah');
                $sisaTagihan = $sale->total_harga - $totalSudahDibayar;

                // Validasi jumlah pembayaran
                if ($request->jumlah > $sisaTagihan) {
                    throw new \Exception(
                        'Jumlah pembayaran melebihi sisa tagihan. Sisa tagihan: Rp ' .
                            number_format($sisaTagihan, 0, ',', '.')
                    );
                }

                $kode = 'PAY-' . date('YmdHis');

                Payment::create([
                    'kode_pembayaran' => $kode,
                    'sale_id' => $sale->id,
                    'tanggal_bayar' => $request->tanggal_bayar,
                    'jumlah' => $request->jumlah,
                    'catatan' => $request->catatan,
                ]);

                // Update status penjualan
                $this->updateSaleStatus($sale);
            });

            return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('payments.create')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load('sale');
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $sale = $payment->sale;

        // Total sudah dibayar dari semua pembayaran lain (exclude payment yang akan diedit)
        $totalBayarLain = $sale->payments()
            ->where('id', '!=', $payment->id)
            ->sum('jumlah');

        // Maksimal yang bisa dibayar = sisa tagihan + jumlah payment saat ini
        $maxBayar = $sale->total_harga - $totalBayarLain;

        return view('payments.edit', compact('payment', 'sale', 'maxBayar', 'totalBayarLain'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        try {
            $request->validate([
                'tanggal_bayar' => 'required|date',
                'jumlah' => 'required|numeric|min:1',
                'catatan' => 'nullable|string',
            ]);

            DB::transaction(function () use ($request, $payment) {
                $sale = $payment->sale;

                // Hitung total pembayaran dari payment lain (exclude payment yang sedang diedit)
                $totalBayarLain = $sale->payments()
                    ->where('id', '!=', $payment->id)
                    ->sum('jumlah');

                // Sisa tagihan yang bisa dibayar
                $maxBayar = $sale->total_harga - $totalBayarLain;

                // Validasi: jumlah pembayaran baru tidak boleh melebihi maksimal yang bisa dibayar
                if ($request->jumlah > $maxBayar) {
                    throw new \Exception(
                        'Jumlah pembayaran melebihi sisa tagihan. Maksimal yang dapat dibayar: Rp ' .
                            number_format($maxBayar, 0, ',', '.')
                    );
                }

                // Cek apakah ada perubahan data
                $oldData = [
                    'tanggal_bayar' => $payment->tanggal_bayar->format('Y-m-d'),
                    'jumlah' => (float) $payment->jumlah,
                    'catatan' => $payment->catatan,
                ];

                $newData = [
                    'tanggal_bayar' => $request->tanggal_bayar,
                    'jumlah' => (float) $request->jumlah,
                    'catatan' => $request->catatan,
                ];

                if ($oldData === $newData) {
                    throw new \Exception('Tidak ada perubahan data yang disimpan.');
                }

                // Update payment
                $payment->update($newData);

                // Update status penjualan
                $this->updateSaleStatus($sale);
            });

            return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        try {
            DB::transaction(function () use ($payment) {
                $sale = $payment->sale;
                $payment->delete();

                // Update status penjualan setelah payment dihapus
                $this->updateSaleStatus($sale);
            });

            return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('payments.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper method untuk update status penjualan
     */
    private function updateSaleStatus(Sale $sale)
    {
        // Refresh data sale untuk memastikan data terbaru
        $sale->refresh();

        // Hitung total yang sudah dibayar
        $totalDibayar = $sale->payments()->sum('jumlah');

        // Update status berdasarkan total pembayaran
        if ($totalDibayar == 0) {
            $sale->update(['status' => 'Belum Dibayar']);
        } elseif ($totalDibayar < $sale->total_harga) {
            $sale->update(['status' => 'Belum Dibayar Sepenuhnya']);
        } else {
            $sale->update(['status' => 'Sudah Dibayar']);
        }
    }

    /**
     * AJAX method untuk mendapatkan info sale (untuk form create/edit)
     */
    public function getSaleInfo(Sale $sale)
    {
        $totalSudahDibayar = $sale->payments()->sum('jumlah');
        $sisaTagihan = $sale->total_harga - $totalSudahDibayar;

        return response()->json([
            'total_harga' => $sale->total_harga,
            'total_sudah_dibayar' => $totalSudahDibayar,
            'sisa_tagihan' => $sisaTagihan,
            'formatted_total_harga' => 'Rp ' . number_format($sale->total_harga, 0, ',', '.'),
            'formatted_total_sudah_dibayar' => 'Rp ' . number_format($totalSudahDibayar, 0, ',', '.'),
            'formatted_sisa_tagihan' => 'Rp ' . number_format($sisaTagihan, 0, ',', '.'),
        ]);
    }
}
