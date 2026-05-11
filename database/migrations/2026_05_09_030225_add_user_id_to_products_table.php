<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('products', function (Blueprint $table) {
        // Menambahkan kolom user_id untuk mencatat siapa pemilik barang
        $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}
}
