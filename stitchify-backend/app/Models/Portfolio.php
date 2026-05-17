<?php
// app/Models/Portfolio.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'tailor_id', 'image_path', 'title', 'description', 'category'
    ];

    public function tailor() {
        return $this->belongsTo(Tailor::class);
    }
}