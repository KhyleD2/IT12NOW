<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Set primary key
    protected $primaryKey = 'Product_ID';

    // Allow mass assignment
    protected $fillable = [
        'Product_Name',
        'Category',
        'Quantity_in_Stock',
        'unit_price',
        'Supplier_ID',
        'expiry_date',
        'reorder_level'
    ];

    // Relationship to Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'Supplier_ID');
    }
}
