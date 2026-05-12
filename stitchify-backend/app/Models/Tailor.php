<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Tailor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'shop_name', 'bio', 'city', 'address',
        'experience_years', 'max_slots', 'available_slots',
        'status', 'specialization', 'base_price'
    ];

    // ===== RELATIONSHIPS =====

    // Tailor ek User hai
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tailor ke bahut saare orders hain
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Tailor ke portfolio images hain
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    // ===== HELPER FUNCTIONS =====

    // Slot available hai ya nahi
    public function hasAvailableSlot(): bool
    {
        return $this->available_slots > 0;
    }

    // Order aane par slot kam karo
    public function decrementSlot(): void
    {
        if ($this->available_slots > 0) {
            $this->decrement('available_slots');
        }
    }

    // Order complete hone par slot wapas barhao
    public function incrementSlot(): void
    {
        if ($this->available_slots < $this->max_slots) {
            $this->increment('available_slots');
        }
    }
}
