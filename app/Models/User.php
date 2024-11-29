<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'updated_at',
        'role',
        'active'
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

    // Relation to order class.
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Accessor for the order count.
    public function getOrderCountAttribute()
    {
        return $this->orders()->count();
    }

    // Define the scope for checking if the user is an administrator
    public function scopeIsAdministrator(Builder $query)
    {
        return $query->where('role', 'administrator');
    }

    // Define the scope for checking if the user is active
    public function scopeIsActive(Builder $query)
    {
        return $query->where('active', '1');
    }
}
