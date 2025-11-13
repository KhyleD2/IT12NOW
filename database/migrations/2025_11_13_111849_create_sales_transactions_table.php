<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_transactions', function (Blueprint $table) {
            $table->id('transaction_ID');
            $table->unsignedBigInteger('Customer_ID');
            $table->unsignedBigInteger('User_ID');
            $table->date('transaction_date');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method');
            $table->string('receipt_number')->unique();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('Customer_ID')->references('Customer_ID')->on('customers')->onDelete('cascade');
            $table->foreign('User_ID')->references('User_ID')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_transactions');
    }
};
