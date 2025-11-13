<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'Customer_ID';

    protected $fillable = [
        'Customer_Name', 'Contact_Number'
    ];

    public function sales()
    {
        return $this->hasMany(SalesTransaction::class, 'Customer_ID');
    }

    public function latestSale()
    {
        return $this->hasOne(SalesTransaction::class, 'Customer_ID')->latest('transaction_date');
    }
}
