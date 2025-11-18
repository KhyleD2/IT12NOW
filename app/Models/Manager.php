<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',       // link to users table
        'fname',         // matches your database column
        'lname',         // matches your database column
        'contact_number',
        'email',
        'password',      // if you store password here (optional, usually only in users table)
    ];
}
