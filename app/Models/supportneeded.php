<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supportneeded extends Model
{
    use HasFactory;

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

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'off_day' => 'integer',
        'complete' => 'integer',
    ];

    /**
     * Relasi ke model Newwarroom
     */
    public function warrooms()
    {
        return $this->hasMany(Newwarroom::class, 'supportneeded_id');
    }

    /**
     * Relasi ke warroom yang aktif (jika ada)
     */
    public function activeWarroom()
    {
        return $this->hasOne(Newwarroom::class, 'supportneeded_id');
    }
}