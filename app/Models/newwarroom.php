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
        'unit_or_telda',
        'notes_to_follow_up',
        'uic',
        'uic_approvals',
        'progress',
        'complete',
        'status',
        'response_uic',
        'peserta',
        'pembahasan',
        'support_needed',
        'info_kompetitor',
        'jumlah_action_plan',
    ];

    protected $casts = [
        'tgl' => 'date',
        'complete' => 'integer',
        'jumlah_action_plan' => 'integer',
        'uic_approvals' => 'json',
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
       ACCESSORS
    ====================== */

    public function getDoneActionPlansCountAttribute()
    {
        return $this->actionPlans()->where('status_action_plan', 'Done')->count();
    }

    public function getProgressActionPlansCountAttribute()
    {
        return $this->actionPlans()->where('status_action_plan', 'Progress')->count();
    }

    public function getEskalasiActionPlansCountAttribute()
    {
        return $this->actionPlans()->where('status_action_plan', 'Eskalasi')->count();
    }

    public function getCompletionPercentageAttribute()
    {
        if ($this->jumlah_action_plan == 0) return 0;
        return round(($this->done_action_plans_count / $this->jumlah_action_plan) * 100, 1);
    }

    public function getUicArrayAttribute()
    {
        return $this->uic ? explode(',', $this->uic) : [];
    }

    public function getUicApprovalsArrayAttribute()
    {
        return $this->uic_approvals ? json_decode($this->uic_approvals, true) : [];
    }

    public function getProgressPercentageAttribute()
    {
        switch ($this->progress) {
            case 'Open': return 0;
            case 'Need Discuss': return 25;
            case 'On Progress': return 75;
            case 'Done': return 100;
            default: return 0;
        }
    }

    public function getProgressColorAttribute()
    {
        switch ($this->progress) {
            case 'Open': return 'bg-red';
            case 'Need Discuss': return 'bg-orange';
            case 'On Progress': return 'bg-yellow';
            case 'Done': return 'bg-green';
            default: return 'bg-gray';
        }
    }

    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'Eskalasi': return 'status-done';
            case 'Action': return 'status-action';
            case 'Support Needed': return 'status-in-progress';
            default: return 'status-empty';
        }
    }

    public function getMainDateAttribute()
    {
        return $this->tgl;
    }

    public function getFormattedMainDateAttribute()
    {
        $date = $this->main_date;
        return $date ? $date->format('d/m/Y') : '-';
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
