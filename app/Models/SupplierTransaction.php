<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierTransaction extends Model
{
    use HasFactory;

    protected $table = 'supplier_transactions';
    protected $primaryKey = 'Supply_transac_ID';
    public $timestamps = true;

    protected $fillable = [
        'Supplier_ID',
        'Product_ID',
        'variety', // ADDED
        'supplier_price',
        'quantity_units',
        'quantity_kilos',
        'supply_date',
        'total_cost',
        'status'
    ];

    protected $casts = [
        'supply_date' => 'date',
        'supplier_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'Supplier_ID', 'Supplier_ID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }

    public function stockIns()
    {
        return $this->hasMany(StockIn::class, 'supplier_transaction_id', 'Supply_transac_ID');
    }
}