<?php
// app/Models/StockIn.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $table = 'stock_ins';
    protected $primaryKey = 'Stock_ID';

    protected $fillable = [
        'Product_ID',
        'date',
        'quantity',
        'price',
        'unit',
        'expiry_date',
        'critical_level'
    ];

    protected $casts = [
        'date' => 'date',
        'expiry_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }
}