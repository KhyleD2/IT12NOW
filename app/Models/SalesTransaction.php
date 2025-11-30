<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTransaction extends Model
{
    protected $table = 'sales_transactions';
    protected $primaryKey = 'transaction_ID';
    public $timestamps = true;

    protected $fillable = [
        'Customer_ID', 'User_ID', 'transaction_date', 'total_amount', 'payment_method', 'receipt_number', 'status'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'Customer_ID', 'Customer_ID');
    }

    public function user() {
        return $this->belongsTo(User::class, 'User_ID', 'User_ID');
    }

    public function details() {
        return $this->hasMany(TransactionDetail::class, 'transaction_ID', 'transaction_ID');
    }

    // ADD THIS NEW RELATIONSHIP
    public function products() {
        return $this->belongsToMany(Product::class, 'transaction_details', 'transaction_ID', 'Product_ID')
            ->withPivot('Quantity', 'Kilo', 'Price')
            ->withTimestamps();
    }
}