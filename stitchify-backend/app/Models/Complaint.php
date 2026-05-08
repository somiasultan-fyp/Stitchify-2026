<?php
// app/Models/Complaint.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'user_id', 'subject', 'message', 'admin_response', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
