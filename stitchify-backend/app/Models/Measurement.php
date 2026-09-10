<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $fillable = [
    'order_id',
    'chest',
    'waist',
    'hips',
    'shoulder',
    'sleeve_length',
    'shirt_length',
    'trouser_length',
    'trouser_waist',
    'neck',
    'additional_notes',
    'details',
];
    protected $casts = [
        'details' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
