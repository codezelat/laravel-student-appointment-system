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
        'branch',
        'purpose',
        'requested_date',
        'admin_time_slot',
        'status',
    ];

    protected $casts = [
        'purpose' => 'array',
        'requested_date' => 'date',
    ];
}
