<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
   public function up()
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        
        // TAMBAHKAN DUA BARIS INI LANGSUNG DI SINI
        $table->foreignId('user_id')->nullable()->constrained('users'); 
        $table->integer('purchase_price')->nullable();
        
        $table->string('product_name');
        $table->integer('price');
        $table->integer('quantity');
        $table->timestamps();
    });
}

public function down()
{
    Schema::table('order_items', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn(['user_id', 'purchase_price']);
    });
}
}