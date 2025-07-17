<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newwarroom extends Model
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
        'supportneeded_id', // Tambahkan kolom ini
    ];

    protected $casts = [
        'tgl' => 'date',
        'jumlah_action_plan' => 'integer',
        'supportneeded_id' => 'integer',
    ];

    /**
     * Relasi ke model Supportneeded
     * Tidak menggunakan foreign key constraint, hanya relasi logis
     */
    public function supportneeded()
    {
        return $this->belongsTo(Supportneeded::class, 'supportneeded_id');
    }
}