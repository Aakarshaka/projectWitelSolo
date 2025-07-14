<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class newwarroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'tgl',
        'agenda',
        'uic',
        'peserta',
        'pembahasan',
        'action_plan',
        'support_needed',
        'info_kompetitor',
        'jumlah_action_plan',
        'update_action_plan',
        'status_action_plan',
    ];
}

