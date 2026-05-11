<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{Product, Order, Category, OrderItem};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, DB};

class AdminController extends Controller
{
    /**
     * Dashboard Utama dengan Filter Tanggal & User
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $user = auth()->user();

        // --- 1. LOGIKA FILTER PENJUALAN ---
        // Load items hanya milik user yang login
        $salesQuery = Order::with(['items' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }]);

        // Filter utama: Hanya ambil Order yang punya item milik user login
        $salesQuery->whereHas('items', function($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        // --- TAMBAHAN: FILTER TANGGAL PENJUALAN ---
        if ($request->filled('start_sales') && $request->filled('end_sales')) {
            $salesQuery->whereBetween('created_at', [
                $request->start_sales . " 00:00:00", 
                $request->end_sales . " 23:59:59"
            ]);
        }

        $orders = $salesQuery->latest()->get();

        // --- 2. LOGIKA FILTER STOK BARANG ---
        $purchaseQuery = Product::query();
        $purchaseQuery->where('user_id', $user->id);

        if ($request->filled('start_purchase') && $request->filled('end_purchase')) {
            $purchaseQuery->whereBetween('created_at', [
                $request->start_purchase . " 00:00:00", 
                $request->end_purchase . " 23:59:59"
            ]);
        }
        $products = $purchaseQuery->latest()->get();

        // --- 3. HITUNG STATISTIK (HANYA PESANAN SELESAI) ---
        $totalSales = 0;
        $totalProfit = 0;

        foreach ($orders as $order) {
            // Sesuai permintaan Anda: Hanya hitung jika status 'Selesai'
            if (in_array($order->status, ['Selesai', 'Success', 'success', 'selesai'])) { 
                foreach ($order->items as $item) {
                    $totalSales += ($item->price * $item->quantity);
                    $profitPerItem = ($item->price - ($item->purchase_price ?? 0)) * $item->quantity;
                    $totalProfit += $profitPerItem;
                }
            }
        }

        // --- 4. DATA UNTUK INFO BOX ---
        $totalPurchases = $products->sum(function($p) {
            return (float)$p->purchase_price * (int)$p->stock;
        });

        return view('admin.dashboard', [
            'products'       => $products,
            'categories'     => $categories,
            'orders'         => $orders,
            'totalSales'     => $totalSales,
            'totalProfit'    => (int)$totalProfit,
            'totalPurchases' => $totalPurchases,
            'totalProducts'  => $products->count()
        ]);
    }

    /**
     * Cetak PDF Pembelian (Aset Gudang)
     */
    public function cetakLaporanPembelian(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Product::query()->where('user_id', auth()->id());
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }

        $products = $query->get();
        $totalModalAset = $products->sum(fn($p) => (float)$p->purchase_price * (int)$p->stock);

        $pdf = Pdf::loadView('admin.laporan_pembelian_pdf', [
            'products' => $products,
            'totalModalAset' => $totalModalAset,
            'date' => ($startDate && $endDate) ? "$startDate s/d $endDate" : date('d F Y')
        ]);

        return $pdf->download('Laporan_Pembelian_' . date('Ymd') . '.pdf');
    }

    /**
     * Cetak PDF Penjualan (Laba Rugi)
     */
    public function cetakLaporan(Request $request) 
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        $query = Order::with(['items' => function($q) {
            $q->where('user_id', auth()->id());
        }])->whereIn('status', ['Selesai', 'Success', 'success', 'selesai', 'paid', 'dikirim']);
        
        $query->whereHas('items', function($q) {
            $q->where('user_id', auth()->id());
        });

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }

        $orders = $query->get();
        $summary = [];
        $grandTotalSales = $grandTotalProfit = 0;

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $product = $item->product ?: Product::where('name', $item->product_name)->first();
                $qty = (int)$item->quantity;
                $hargaJual = (float)$item->price;
                $hargaBeli = $product ? (float)$product->purchase_price : 0;
                $laba = ($hargaJual - $hargaBeli) * $qty;

                $identifier = $product ? $product->id : 'manual-'.$item->id;
                if (!isset($summary[$identifier])) {
                    $summary[$identifier] = [
                        'sku' => $product ? $product->product_code : '-',
                        'name' => $product ? $product->name : ($item->product_name ?? 'Produk Terhapus'),
                        'qty' => 0, 'total_sales' => 0, 'total_profit' => 0, 'price_unit' => $hargaJual
                    ];
                }
                $summary[$identifier]['qty'] += $qty;
                $summary[$identifier]['total_sales'] += ($hargaJual * $qty);
                $summary[$identifier]['total_profit'] += $laba;
                $grandTotalSales += ($hargaJual * $qty);
                $grandTotalProfit += $laba;
            }
        }

        $pdf = Pdf::loadView('admin.laporan_pdf', [
            'summary' => $summary,
            'grandTotalSales' => $grandTotalSales,
            'grandTotalProfit' => $grandTotalProfit,
            'date' => ($startDate && $endDate) ? "$startDate s/d $endDate" : date('d F Y')
        ]);
        return $pdf->download('Laporan_Penjualan.pdf');
    }

    /**
     * Update Status Pesanan
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status Berhasil Diperbarui!');
    }

    // --- CRUD PRODUK ---
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $sku = $request->product_code;
        if (empty($sku)) {
            $sku = 'SKU-' . strtoupper(substr($request->category, 0, 3)) . '-' . rand(1000, 9999);
        }

        $userId = auth()->id();
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('products', 'public') : null;

        Product::create([
            'user_id' => $userId,
            'product_code' => $request->product_code,
            'name' => $request->name,
            'category' => $request->category,
            'purchase_price' => $request->purchase_price,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if (auth()->user()->role !== 'admin' && $product->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        $product->update($request->all());
        return redirect()->back()->with('success', 'Data barang diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if (auth()->user()->role !== 'admin' && $product->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        $product->delete();
        return redirect()->back()->with('success', 'Barang berhasil dihapus!');
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name']);
        Category::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Kategori ditambahkan!');
    }

    public function destroyCategory($id) {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Kategori dihapus!');
    }
}