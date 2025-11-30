<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    protected $table = 'stock_ins';
    
    // FIXED: Primary key is Stock_ID (not StockIn_ID)
    protected $primaryKey = 'Stock_ID';
    
    public $timestamps = true;

    protected $fillable = [
        'Product_ID',
        'supplier_transaction_id',
        'date',
        'quantity',
        'price',
        'unit',
        'expiry_date',
        'critical_level',
        'variety',
    ];

    protected $casts = [
        'date' => 'date',
        'expiry_date' => 'date',
    ];

    // Relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }

    // Relationship to SupplierTransaction
    public function supplierTransaction()
    {
        return $this->belongsTo(SupplierTransaction::class, 'supplier_transaction_id', 'Supply_transac_ID');
    }
}