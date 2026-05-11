<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',     // Pastikan ini ada karena kita pakai ID produk
        'user_id',        // PENTING: Untuk mencatat siapa pemilik barang (Seller)
        'product_name',
        'price',
        'purchase_price', // PENTING: Untuk menghitung laba di dashboard
        'quantity'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Tambahkan relasi ke user agar sistem tahu siapa penjualnya
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}