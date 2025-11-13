<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $primaryKey = 'User_ID';

    protected $fillable = [
        'fname', 'lname', 'contact_number', 'role', 'email', 'password'
    ];

    protected $hidden = [
        'password',
    ];
}
