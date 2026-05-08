<?php
// app/Models/Customer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'address', 'city', 'date_of_birth', 'gender'
    ];

    // ===== RELATIONSHIPS =====

    // Customer ek User hai
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Customer ke bahut saare orders hain
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
