<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Newwarroom extends Model
{
    protected $table = 'newwarrooms';

    protected $fillable = [
        'tgl',
        'supportneeded_id',
        'agenda',
        'uic',
        'peserta',
        'pembahasan',
        'support_needed',
        'info_kompetitor',
        'jumlah_action_plan',
    ];

    protected $casts = [
        'tgl' => 'date',
        'jumlah_action_plan' => 'integer',
    ];

    /* ======================
       RELATIONSHIPS
    ====================== */

    public function actionPlans(): HasMany
    {
        return $this->hasMany(ActionPlan::class, 'newwarroom_id');
    }

    public function supportneeded(): BelongsTo
    {
        return $this->belongsTo(Supportneeded::class, 'supportneeded_id');
    }

    /* ======================
       SCOPES (SEARCH & FILTER)
    ====================== */

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('agenda', 'like', "%$search%")
              ->orWhere('uic', 'like', "%$search%")
              ->orWhere('peserta', 'like', "%$search%")
              ->orWhere('pembahasan', 'like', "%$search%")
              ->orWhere('support_needed', 'like', "%$search%")
              ->orWhere('info_kompetitor', 'like', "%$search%")
              ->orWhereHas('actionPlans', function ($actionQuery) use ($search) {
                  $actionQuery->where('action_plan', 'like', "%$search%")
                              ->orWhere('update_action_plan', 'like', "%$search%")
                              ->orWhere('status_action_plan', 'like', "%$search%");
              });
        });
    }

    public function scopeByMonth($query, $month)
    {
        return $query->whereMonth('tgl', $month);
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('tgl', $year);
    }
}