<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $primaryKey = 'Supplier_ID';

    protected $fillable = [
        'Supplier_Name', 'contact_person', 'contact_number', 'address', 'payment_terms'
    ];

    // Optional: get products supplied
    public function products() {
        return $this->hasMany(Product::class, 'Supplier_ID');
    }
}
