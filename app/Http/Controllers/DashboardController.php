<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_penjualan', [$request->start_date, $request->end_date]);
        }

        $sales = $query->with('items.item')->get();

        // Debug data
        logger('Total sales: ' . $sales->count());
        logger('Sales data: ' . $sales->pluck('id'));

        // Widgets
        $totalTransactions = $sales->count();
        $totalRevenue = $sales->sum('total_harga');
        $totalQty = $sales->flatMap(fn($s) => $s->items)->sum('quantity');

        // Chart: Revenue per Month - PERBAIKI GROUPING
        $revenueByMonth = $sales->groupBy(function ($sale) {
            return \Carbon\Carbon::parse($sale->tanggal_penjualan)->format('Y-m');
        })->map(function ($monthSales) {
            return $monthSales->sum('total_harga');
        });

        // Jika tidak ada data, beri nilai default
        if ($revenueByMonth->isEmpty()) {
            $revenueByMonth = [
                'labels' => ['No Data'],
                'data' => [0],
            ];
        } else {
            $revenueByMonth = [
                'labels' => $revenueByMonth->keys()->map(function ($key) {
                    return \Carbon\Carbon::createFromFormat('Y-m', $key)->format('M Y');
                })->toArray(),
                'data' => $revenueByMonth->values()->toArray(),
            ];
        }

        // Chart: Qty by Item - PERBAIKI DENGAN NULL SAFETY
        $qtyByItem = $sales->flatMap(function ($sale) {
            return $sale->items->map(function ($item) {
                return [
                    'item_name' => $item->item->nama ?? 'Unknown Item',
                    'quantity' => $item->quantity
                ];
            });
        })->groupBy('item_name')
            ->map(function ($group) {
                return $group->sum('quantity');
            });

        if ($qtyByItem->isEmpty()) {
            $qtyByItem = [
                'labels' => ['No Data'],
                'data' => [0],
            ];
        } else {
            $qtyByItem = [
                'labels' => $qtyByItem->keys()->toArray(),
                'data' => $qtyByItem->values()->toArray(),
            ];
        }

        // Debug final data
        logger('Revenue by Month: ' . json_encode($revenueByMonth));
        logger('Qty by Item: ' . json_encode($qtyByItem));

        return view('dashboard.index', compact(
            'totalTransactions',
            'totalRevenue',
            'totalQty',
            'revenueByMonth',
            'qtyByItem'
        ));
    }
}
