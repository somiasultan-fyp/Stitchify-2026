<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

 class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'profile_image',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ===== RELATIONSHIPS =====

    public function tailor()
    {
        return $this->hasOne(Tailor::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    // ===== HELPER FUNCTIONS =====

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTailor(): bool
    {
        return $this->role === 'tailor';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}