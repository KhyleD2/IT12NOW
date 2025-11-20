<?php
// database/migrations/xxxx_xx_xx_add_supplier_price_to_supplier_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('supplier_transactions', function (Blueprint $table) {
            $table->decimal('supplier_price', 10, 2)->default(0)->after('Product_ID');
        });
    }

    public function down()
    {
        Schema::table('supplier_transactions', function (Blueprint $table) {
            $table->dropColumn('supplier_price');
        });
    }
};