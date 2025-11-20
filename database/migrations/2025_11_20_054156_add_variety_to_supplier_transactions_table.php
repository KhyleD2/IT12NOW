<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('supplier_transactions', function (Blueprint $table) {
            $table->string('variety')->nullable()->after('Product_ID');
        });
    }

    public function down()
    {
        Schema::table('supplier_transactions', function (Blueprint $table) {
            $table->dropColumn('variety');
        });
    }
};