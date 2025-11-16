// database/migrations/xxxx_create_stock_ins_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id('Stock_ID');
            $table->unsignedBigInteger('Product_ID');
            $table->date('date');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->string('unit');
            $table->date('expiry_date')->nullable();
            $table->integer('critical_level')->default(5);
            $table->timestamps();
            
            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_ins');
    }
};