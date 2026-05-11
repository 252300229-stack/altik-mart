<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;

// --- 1. Public Routes ---
Route::get('/', [ProductController::class, 'index'])->name('home');

// --- 2. Authentication Routes ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- 3. Protected Routes (Harus Login) ---
Route::middleware(['auth'])->group(function () {
    
    // Fitur Pembeli
    Route::get('/cart', [ProductController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{id}', [ProductController::class, 'addToCart'])->name('cart.add');
    Route::patch('/update-cart', [ProductController::class, 'updateCart'])->name('update.cart');
    Route::delete('/remove-from-cart', [ProductController::class, 'removeFromCart'])->name('remove.from.cart');
    
    Route::get('/checkout', [ProductController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/place', [ProductController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/my-orders', [ProductController::class, 'myOrders'])->name('my.orders');

    Route::get('/profile', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::get('/payment-success', function() {
        session()->forget('cart');
        return redirect('/')->with('success', 'Transaksi selesai!');
    })->name('payment.success');

 // --- 4. AREA DASHBOARD (ADMIN & SELLER/SISWA) ---
    Route::middleware(['isSeller'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard Utama
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        // Produk
        Route::post('/products', [AdminController::class, 'store'])->name('products.store');
        Route::match(['put', 'patch'], '/products/{id}', [AdminController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [AdminController::class, 'destroy'])->name('products.destroy');
        
        // --- PINDAHKAN KE SINI AGAR SELLER BISA SIMPAN KATEGORI ---
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');

        // Pesanan & Laporan
        Route::patch('/orders/{id}/status', [AdminController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/laporan/penjualan/cetak', [AdminController::class, 'cetakLaporan'])->name('cetak');
        Route::get('/laporan/pembelian/cetak', [AdminController::class, 'cetakLaporanPembelian'])->name('cetakPembelian');
        Route::get('/admin/cetak-laporan', [AdminController::class, 'cetakLaporan'])->name('admin.cetak');

        // --- RUTE KHUSUS ADMIN SAJA (Misal: Hapus Kategori) ---
        Route::middleware(['isAdmin'])->group(function () {
            // Seller bisa tambah, tapi hanya Admin yang boleh hapus kategori
            Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
        });

    });
});