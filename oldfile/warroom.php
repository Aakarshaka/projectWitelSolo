<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class warroom extends Model
{
    protected $fillable = [
        'tgl',
        'agenda',
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
