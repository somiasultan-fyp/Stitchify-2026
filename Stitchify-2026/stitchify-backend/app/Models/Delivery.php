<?php
// app/Models/Delivery.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'order_id',
        'tracking_id',
        'type',
        'status',
        'courier_name',
        'courier_tracking_ref',
        'pickup_address',
        'delivery_address',
        'estimated_date',
        'notes',
    ];

    protected $casts = [
        'estimated_date' => 'date',
    ];

    // Relation to Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Generate unique tracking ID 
    public static function generateTrackingId(): string
    {
        do {
            $id = 'STF-' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('tracking_id', $id)->exists());

        return $id;
    }

    // Status readable label
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'scheduled'               => 'Delivery Scheduled',
            'picked_up_from_customer' => 'Fabric Picked Up',
            'delivered_to_tailor'     => 'Fabric at Tailor',
            'stitching_in_progress'   => 'Stitching in Progress',
            'picked_up_from_tailor'   => 'Order Picked Up',
            'out_for_delivery'        => 'Out for Delivery',
            'delivered'               => 'Delivered',
            default                   => ucfirst($this->status),
        };
    }

    // Progress percentage
    public function getProgressAttribute(): int
    {
        return match($this->status) {
            'scheduled'               => 10,
            'picked_up_from_customer' => 25,
            'delivered_to_tailor'     => 40,
            'stitching_in_progress'   => 55,
            'picked_up_from_tailor'   => 75,
            'out_for_delivery'        => 90,
            'delivered'               => 100,
            default                   => 0,
        };
    }
}
