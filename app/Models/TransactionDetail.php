<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $primaryKey = 'detail_ID';

    protected $fillable = [
        'transaction_ID', 'Product_ID', 'Quantity', 'unit_price'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'Product_ID');
    }

    public function transaction() {
        return $this->belongsTo(SalesTransaction::class, 'transaction_ID');
    }
}
