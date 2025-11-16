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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // ============================================
    // ROLE HELPER METHODS
    // ============================================

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a manager
     */
    public function isManager()
    {
        return $this->role === 'manager';
    }

    /**
     * Check if user is a cashier
     */
    public function isCashier()
    {
        return $this->role === 'cashier';
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    /**
     * Get user's full name
     */
    public function getFullNameAttribute()
    {
        return "{$this->fname} {$this->lname}";
    }
}