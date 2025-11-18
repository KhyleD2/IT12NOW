<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'contact_number',
        'email',
        'password',
    ];
}
