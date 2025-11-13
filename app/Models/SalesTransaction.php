<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTransaction extends Model
{
    protected $primaryKey = 'transaction_ID';

    protected $fillable = [
        'Customer_ID', 'User_ID', 'transaction_date', 'total_amount', 'payment_method', 'receipt_number', 'status'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'Customer_ID');
    }

    public function user() {
        return $this->belongsTo(User::class, 'User_ID');
    }

    public function details() {
        return $this->hasMany(TransactionDetail::class, 'transaction_ID');
    }
}
