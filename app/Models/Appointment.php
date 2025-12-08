<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'full_name',
        'phone_number',
        'address',
        'branch',
        'purpose',
        'appointment_date',
        'time_slot',
        'status',
    ];

    protected $casts = [
        'purpose' => 'array',
        'appointment_date' => 'date',
    ];
}
