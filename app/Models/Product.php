<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'Product_ID';

    protected $fillable = [
        'Product_Name',
        'Category',
        'variety',
        'description',
        'image',
        'Supplier_ID',
        'Quantity_in_Stock',
        'unit_price',
        'expiry_date',
        'reorder_level'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'Supplier_ID', 'Supplier_ID');
    }

    public function stockIns()
    {
        return $this->hasMany(StockIn::class, 'Product_ID', 'Product_ID');
    }

    public function getTotalStockAttribute()
    {
        // Return current stock, not sum of all stock-ins
        return $this->Quantity_in_Stock;
    }

    public function getEarliestExpiryAttribute()
    {
        return $this->expiry_date;
    }
}