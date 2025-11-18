<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_transactions', function (Blueprint $table) {
            $table->id('Supply_transac_ID');
            $table->unsignedBigInteger('Supplier_ID');
            $table->unsignedBigInteger('Product_ID');
            $table->integer('quantity_units');
            $table->decimal('quantity_kilos', 10, 2);
            $table->date('supply_date');
            $table->decimal('total_cost', 10, 2);
            $table->timestamps();

            $table->foreign('Supplier_ID')->references('Supplier_ID')->on('suppliers')->onDelete('cascade');
            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_transactions');
    }
};
