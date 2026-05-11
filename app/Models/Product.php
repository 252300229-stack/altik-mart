<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id', // WAJIB TAMBAHKAN INI agar id penjual bisa tersimpan
        'product_code',
        'name', 
        'category', 
        'purchase_price', 
        'price', 
        'stock', 
        'image'
    ];

    /**
     * Relasi: Satu Produk dimiliki oleh satu User (Penjual)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} // Penutup Class