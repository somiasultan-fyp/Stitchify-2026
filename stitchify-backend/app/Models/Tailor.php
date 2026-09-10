<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Tailor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'bio',
        'city',
        'address',
        'experience_years',
        'max_slots',
        'available_slots',
        'status',
        'base_max_slots',
        'last_slot_reset_at',
        'specialization',
        'base_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function hasAvailableSlot(): bool
    {
        return $this->available_slots > 0;
    }

    public function decrementSlot(): void
    {
        if ($this->available_slots > 0) {
            $this->decrement('available_slots');
        }
    }

    public function incrementSlot(): void
    {
        if ($this->available_slots < $this->max_slots) {
            $this->increment('available_slots');
        }
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function resetSlotsToBase()
    {
        $this->available_slots = $this->base_max_slots;
        $this->last_slot_reset_at = now();
        $this->save();
    }
}