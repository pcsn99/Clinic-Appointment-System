<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'is_present',
        'status',
    ];

    protected $casts = [
        'is_present' => 'boolean',
    ];

    // 🔗 Relationship: Appointment belongs to a student
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 Relationship: Appointment belongs to a schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
