<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('Product_ID');
            $table->string('Product_Name');
            $table->string('Category');
            $table->integer('Quantity_in_Stock')->default(0);
            $table->decimal('unit_price', 10, 2);
            $table->unsignedBigInteger('Supplier_ID');
            $table->date('expiry_date')->nullable();
            $table->integer('reorder_level')->default(0);
            $table->timestamps();

            $table->foreign('Supplier_ID')->references('Supplier_ID')->on('suppliers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
