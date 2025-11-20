<?php
// app/Models/Product.php

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
        return $this->stockIns()->sum('quantity');
    }

    public function getEarliestExpiryAttribute()
    {
        return $this->stockIns()
            ->whereNotNull('expiry_date')
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->first()?->expiry_date;
    }

    public function updateFromStockIns()
    {
        $totalQuantity = $this->stockIns()->sum('quantity');
        
        $earliestExpiry = $this->stockIns()
            ->whereNotNull('expiry_date')
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->first();
        
        $latestStockIn = $this->stockIns()
            ->orderBy('date', 'desc')
            ->first();

        $this->update([
            'Quantity_in_Stock' => $totalQuantity,
            'expiry_date' => $earliestExpiry ? $earliestExpiry->expiry_date : null,
            'unit_price' => $latestStockIn ? $latestStockIn->price : $this->unit_price,
        ]);
    }
}