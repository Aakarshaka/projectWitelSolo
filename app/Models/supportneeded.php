<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class supportneeded extends Model
{
    protected $fillable = [
        'agenda',
        'unit_or_telda',
        'start_date',
        'end_date',
        'off_day',
        'notes_to_follow_up',
        'uic',
        'progress',
        'complete',
        'status',
        'response_uic',
    ];
}
