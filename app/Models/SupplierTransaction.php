<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierTransaction extends Model
{
    protected $primaryKey = 'Supply_transac_ID';

    protected $fillable = [
        'Supplier_ID',
        'Product_ID',
        'quantity_units',
        'quantity_kilos',
        'supply_date',
        'total_cost',
        'status', // pending, completed, cancelled, paid
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'Supplier_ID', 'Supplier_ID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }
}
