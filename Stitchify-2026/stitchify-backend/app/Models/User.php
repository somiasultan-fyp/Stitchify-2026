<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 
        'phone', 'profile_image', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ===== RELATIONSHIPS =====

    // User ek Tailor bhi ho sakta hai
    public function tailor()
    {
        return $this->hasOne(Tailor::class);
    }

    // User ek Customer bhi ho sakta hai
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    // User ki notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // User ki complaints
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    // ===== HELPER FUNCTIONS =====

    // Check karo kya yeh admin hai
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Check karo kya yeh tailor hai
    public function isTailor(): bool
    {
        return $this->role === 'tailor';
    }

    // Check karo kya yeh customer hai
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}