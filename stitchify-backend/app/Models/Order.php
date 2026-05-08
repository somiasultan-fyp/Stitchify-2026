<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'customer_id', 'tailor_id', 'dress_type',
        'special_instructions', 'fabric_provided_by', 'fabric_details',
        'price', 'advance_paid', 'expected_delivery_date',
        'actual_delivery_date', 'status', 'payment_status',
        'delivery_type', 'tracking_id'
    ];

    // ===== RELATIONSHIPS =====

    // Order Customer ka hai
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Order Tailor ka hai
    public function tailor()
    {
        return $this->belongsTo(Tailor::class);
    }

    // Order ki measurements
    public function measurement()
    {
        return $this->hasOne(Measurement::class);
    }

    // Order ke payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ===== HELPER FUNCTIONS =====

    // Unique order number generate karo
    public static function generateOrderNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'ORD-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        // Result: ORD-2025-0001, ORD-2025-0002, etc.
    }
}