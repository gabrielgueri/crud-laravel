<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaptopReservation extends Model
{
    protected $fillable = [
        'requester_name',
        'laptop_asset_number',
        'student_class',
        'teacher_name',
        'subject',
        'includes_charger',
        'charger_code',
    ];

    protected $casts = [
        'includes_charger' => 'boolean',
    ];
}
