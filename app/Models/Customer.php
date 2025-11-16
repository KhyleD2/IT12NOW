<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'Customer_ID';

    protected $fillable = [
        'Customer_Name', 'Contact_Number'
    ];

    // Get ALL sales transactions for this customer
    public function sales()
    {
        return $this->hasMany(SalesTransaction::class, 'Customer_ID', 'Customer_ID');
    }

    // Get only the latest sale (if you need it somewhere)
    public function latestSale()
    {
        return $this->hasOne(SalesTransaction::class, 'Customer_ID', 'Customer_ID')->latest('transaction_date');
    }
}