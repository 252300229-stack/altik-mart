<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // database/migrations/xxxx_add_shipping_to_orders_table.php
public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('shipping_method')->nullable()->after('address');
        $table->integer('shipping_cost')->default(0)->after('shipping_method');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
