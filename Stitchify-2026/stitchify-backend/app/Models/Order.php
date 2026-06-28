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
        'delivery_type', 'tracking_id', 'delivery_days', 'rejection_reason',
        'design_image', 'accepted_at', 'rejected_at',
    ];

    protected $casts = [
    'expected_delivery_date' => 'datetime',
    'actual_delivery_date' => 'datetime',
    'accepted_at' => 'datetime',
    'rejected_at' => 'datetime',
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


   public static function generateOrderNumber(): string
{
    $year = date('Y');
    $last = self::whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();
    
    if ($last) {
        $lastNumber = (int) substr($last->order_number, -4);
        $newNumber  = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    
    return 'ORD-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}
    public function delivery()
{
    return $this->hasOne(Delivery::class);
}
}