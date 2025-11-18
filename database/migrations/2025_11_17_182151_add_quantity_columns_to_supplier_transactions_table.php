<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplier_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('supplier_transactions', 'quantity_units')) {
                $table->integer('quantity_units')->default(0);
            }

            if (!Schema::hasColumn('supplier_transactions', 'quantity_kilos')) {
                $table->decimal('quantity_kilos', 10, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('supplier_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('supplier_transactions', 'quantity_units')) {
                $table->dropColumn('quantity_units');
            }

            if (Schema::hasColumn('supplier_transactions', 'quantity_kilos')) {
                $table->dropColumn('quantity_kilos');
            }
        });
    }
};

