<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchasePriceToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan pengecekan if agar tidak error jika kolom sudah ada
            if (!Schema::hasColumn('products', 'purchase_price')) {
                // Pilih salah satu: integer atau decimal. Untuk harga disarankan decimal atau bigInteger
                $table->integer('purchase_price')->after('price')->default(0)->nullable();
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
    }
}