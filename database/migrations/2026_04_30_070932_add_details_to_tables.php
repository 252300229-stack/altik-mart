<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        // Update untuk tabel products
        Schema::table('products', function (Blueprint $table) {
            // Hanya tambah jika kolom purchase_price BELUM ADA
            if (!Schema::hasColumn('products', 'purchase_price')) {
                $table->decimal('purchase_price', 15, 2)->after('name')->default(0);
            }
        });

        // Update untuk tabel orders
        Schema::table('orders', function (Blueprint $table) {
            // Hanya tambah jika kolom status BELUM ADA
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->after('total_price')->default('pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'purchase_price')) {
                $table->dropColumn('purchase_price');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}