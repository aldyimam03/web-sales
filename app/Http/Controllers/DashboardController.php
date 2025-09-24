<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $allSalesQuery = Sale::query();

        $paidSalesQuery = Sale::query()->where('status', 'Sudah Dibayar');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $allSalesQuery->whereBetween('tanggal_penjualan', [$request->start_date, $request->end_date]);
            $paidSalesQuery->whereBetween('tanggal_penjualan', [$request->start_date, $request->end_date]);
        }

        // Eksekusi query
        $allSales = $allSalesQuery->with('items.item')->get();
        $paidSales = $paidSalesQuery->with('items.item')->get();

        // Widget
        $totalTransactions = $allSales->count();
        $totalRevenue = $paidSales->sum('total_harga'); 
        $totalQty = $paidSales->flatMap(fn($s) => $s->items)->sum('quantity'); 

        // Chart: Revenue per bulan (hanya yang sudah dibayar)
        $revenueByMonth = $paidSales->groupBy(function ($sale) {
            return \Carbon\Carbon::parse($sale->tanggal_penjualan)->format('Y-m');
        })->map(function ($monthSales) {
            return $monthSales->sum('total_harga');
        });

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

        // Chart: Qty per Item (hanya yang sudah dibayar)
        $qtyByItem = $paidSales->flatMap(function ($sale) {
            return $sale->items->map(function ($item) {
                return [
                    'item_name' => $item->item->nama ?? 'Item Tidak Diketahui',
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

        return view('dashboard.index', compact(
            'totalTransactions',
            'totalRevenue',
            'totalQty',
            'revenueByMonth',
            'qtyByItem'
        ));
    }
}
