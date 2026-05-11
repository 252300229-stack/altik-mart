<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // order_id unik untuk referensi Midtrans (contoh: ALTIK-12345)
            // Kunci untuk memperbaiki error: Unknown column 'order_id'
            $table->string('order_id')->unique(); 
            
            // user_id dibuat nullable agar jika pembeli belum login, data tetap tersimpan
            // Solusi untuk error: Foreign key constraint fails
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            
            // Detail Pengiriman
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('address');
            
            // Informasi Pembayaran
            $table->integer('total_price');
            
            /**
             * Status Pesanan:
             * pending = Belum dibayar
             * dikemas = Sudah bayar, sedang disiapkan
             * dikirim = Dalam perjalanan
             * selesai = Pesanan diterima
             */
            $table->string('status')->default('pending'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}