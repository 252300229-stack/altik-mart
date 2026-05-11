<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function financeReport(Request $request)
{
    // Filter Tanggal
    $startDate = $request->input('start_date', date('Y-m-01'));
    $endDate = $request->input('end_date', date('Y-m-d'));

    // 1. LAPORAN PENJUALAN (Uang Masuk)
    // Kita ambil dari tabel orders yang statusnya sudah sukses
    $sales = \App\Models\Order::whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"])
                ->where('status', 'success')
                ->get();
    
    $totalSales = $sales->sum('total_price');

    // 2. LAPORAN PEMBELIAN (Uang Keluar / Detail Item Terjual)
    // Kita ambil detail item dari order_items yang sukses untuk melihat barang apa saja yang keluar
    $purchases = \App\Models\OrderItem::whereHas('order', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate])
                          ->where('status', 'success');
                })->get();

    return view('admin.report', compact('sales', 'purchases', 'totalSales', 'startDate', 'endDate'));
}
}
