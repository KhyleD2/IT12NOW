<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            // Add supplier_transaction_id column (nullable for existing records)
            $table->unsignedBigInteger('supplier_transaction_id')->nullable()->after('Product_ID');
            
            // Add foreign key constraint
            $table->foreign('supplier_transaction_id')
                  ->references('Supply_transac_ID')
                  ->on('supplier_transactions')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['supplier_transaction_id']);
            // Then drop column
            $table->dropColumn('supplier_transaction_id');
        });
    }
};