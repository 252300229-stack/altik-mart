<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 
        'user_id', 
        'customer_name', 
        'customer_phone', // Kolom ini yang akan kita panggil di JS
        'address', 
        'shipping_method', 
        'shipping_cost', 
        'total_price', 
        'status'
    ];

    // Relasi ke tabel OrderItem
    public function items()
    {
        // Menentukan foreign key 'order_id' dan local key 'id'
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}