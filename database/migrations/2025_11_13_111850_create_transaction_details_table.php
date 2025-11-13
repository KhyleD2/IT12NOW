<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id('detail_ID');
            $table->unsignedBigInteger('transaction_ID');
            $table->unsignedBigInteger('Product_ID');
            $table->integer('Quantity');
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();

            $table->foreign('transaction_ID')->references('transaction_ID')->on('sales_transactions')->onDelete('cascade');
            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
