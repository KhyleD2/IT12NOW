<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add variety and description if not exists
            $table->string('variety')->nullable()->after('Category');
            $table->text('description')->nullable()->after('variety');
            $table->string('image')->nullable()->after('description');
            
            // We'll keep unit_price and Quantity_in_Stock for now but these will be calculated from stock_ins
            // expiry_date will also come from stock_ins (we'll use the earliest expiring batch)
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['variety', 'description', 'image']);
        });
    }
};