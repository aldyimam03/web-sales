<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Menampilkan daftar penjualan
     */
    public function index(Request $request)
    {
        try {
            $query = Sale::with('items.item')->latest();

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_penjualan', [
                    $request->start_date,
                    $request->end_date
                ]);
            } elseif ($request->filled('start_date')) {
                $query->whereDate('tanggal_penjualan', '>=', $request->start_date);
            } elseif ($request->filled('end_date')) {
                $query->whereDate('tanggal_penjualan', '<=', $request->end_date);
            }

            $sales = $query->paginate(10);
            $pageTitle = 'Halaman Penjualan';

            return view('sales.index', compact('sales', 'pageTitle'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form tambah penjualan
     */
    public function create()
    {
        try {
            $items = Item::all();
            $kodePenjualan = 'SALE-' . now()->format('YmdHis');
            return view('sales.create', compact('items', 'kodePenjualan'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Simpan penjualan baru
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'tanggal_penjualan'   => 'required|date',
                'items.*.item_id'     => 'required|exists:items,id',
                'items.*.quantity'    => 'required|integer|min:1',
                'items.*.price'       => 'required|numeric|min:0',
            ]);

            DB::transaction(function () use ($request) {
                $sale = Sale::create([
                    'kode_penjualan'    => $request->kode_penjualan ?? 'SALE-' . now()->format('YmdHis'),
                    'tanggal_penjualan' => $request->tanggal_penjualan,
                    'status'            => $request->status ?? 'Belum Dibayar',
                    'total_harga'       => 0,
                    'user_id'           => auth()->id(),
                ]);

                $total = 0;

                foreach ($request->items as $row) {
                    $subtotal = $row['quantity'] * $row['price'];
                    $total += $subtotal;

                    SaleItem::create([
                        'sale_id'     => $sale->id,
                        'items_id'    => $row['item_id'],
                        'quantity'    => $row['quantity'],
                        'harga'       => $row['price'],
                        'total_harga' => $subtotal,
                    ]);
                }

                $sale->update(['total_harga' => $total]);
            });

            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Detail penjualan
     */
    public function show(Sale $sale)
    {
        try {
            $sale->load('items.item');
            return view('sales.show', compact('sale'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form edit penjualan
     */
    public function edit(Sale $sale)
    {
        try {
            $items = Item::all();
            $sale->load('items');
            return view('sales.edit', compact('sale', 'items'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update penjualan
     */
    public function update(Request $request, Sale $sale)
    {
        try {
            if ($sale->status === 'Sudah Dibayar') {
                return redirect()->route('sales.index')->with('error', 'Penjualan sudah dibayar dan tidak bisa diubah.');
            }

            $request->validate([
                'tanggal_penjualan'   => 'required|date',
                'items'               => 'required|array|min:1',
                'items.*.item_id'     => 'required|exists:items,id',
                'items.*.quantity'    => 'required|integer|min:1',
                'items.*.price'       => 'required|numeric|min:0',
            ]);

            // normalisasi incoming items
            $incoming = array_values($request->input('items', []));

            $normalizedIncoming = array_map(function ($r) {
                return [
                    'item_id'  => (int) ($r['item_id'] ?? 0),
                    'quantity' => (int) ($r['quantity'] ?? 0),
                    'price'    => (float) ($r['price'] ?? 0),
                ];
            }, $incoming);

            // load current items
            $sale->load('items');

            $currentItems = $sale->items->map(function ($si) {
                return [
                    'item_id'  => (int) $si->items_id,
                    'quantity' => (int) $si->quantity,
                    'price'    => (float) $si->harga,
                ];
            })->toArray();

            // cek apakah items sama (urutan juga dibandingkan)
            $sameItems = count($currentItems) === count($normalizedIncoming);
            if ($sameItems) {
                foreach ($currentItems as $i => $cur) {
                    $inc = $normalizedIncoming[$i];
                    if (
                        $cur['item_id'] !== $inc['item_id'] ||
                        $cur['quantity'] !== $inc['quantity'] ||
                        abs($cur['price'] - $inc['price']) > 0.0001
                    ) {
                        $sameItems = false;
                        break;
                    }
                }
            }

            // cek tanggal
            $saleDate = Carbon::parse($sale->tanggal_penjualan)->toDateString();
            $reqDate  = Carbon::parse($request->tanggal_penjualan)->toDateString();

            if ($sameItems && $saleDate === $reqDate) {
                return redirect()->back()->withInput()->with('error', 'Tidak ada perubahan data.');
            }

            // lakukan update
            DB::transaction(function () use ($sale, $request, $normalizedIncoming) {
                $sale->update([
                    'tanggal_penjualan' => $request->tanggal_penjualan,
                ]);

                // hapus item lama
                $sale->items()->delete();

                $total = 0;
                foreach ($normalizedIncoming as $row) {
                    $subtotal = $row['quantity'] * $row['price'];
                    $total += $subtotal;

                    SaleItem::create([
                        'sale_id'     => $sale->id,
                        'items_id'    => $row['item_id'],
                        'quantity'    => $row['quantity'],
                        'harga'       => $row['price'],
                        'total_harga' => $subtotal,
                    ]);
                }

                $sale->update(['total_harga' => $total]);
            });

            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil diperbarui.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus penjualan
     */
    public function destroy(Sale $sale)
    {
        try {
            if ($sale->status === 'Sudah Dibayar') {
                return redirect()->route('sales.index')->with('error', 'Data tidak bisa dihapus karena sudah dibayar.');
            }

            $sale->items()->delete();
            $sale->delete();

            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
