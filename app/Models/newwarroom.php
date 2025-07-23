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
        'agenda',
        'uic',
        'peserta',
        'pembahasan',
        'support_needed',
        'info_kompetitor',
        'jumlah_action_plan',
        'supportneeded_id',
    ];

    protected $casts = [
        'tgl' => 'date',
        'jumlah_action_plan' => 'integer',
    ];

    /**
     * Relationship dengan ActionPlan
     */
    public function actionPlans(): HasMany
    {
        return $this->hasMany(ActionPlan::class, 'newwarroom_id');
    }

    /**
     * Relationship dengan Supportneeded
     */
    public function supportneeded(): BelongsTo
    {
        return $this->belongsTo(Supportneeded::class, 'supportneeded_id');
    }

    /**
     * Accessor untuk mendapatkan jumlah action plan yang sudah done
     */
    public function getDoneActionPlansCountAttribute()
    {
        return $this->actionPlans()->where('status_action_plan', 'Done')->count();
    }

    /**
     * Accessor untuk mendapatkan jumlah action plan yang sedang progress
     */
    public function getProgressActionPlansCountAttribute()
    {
        return $this->actionPlans()->where('status_action_plan', 'Progress')->count();
    }

    /**
     * Accessor untuk mendapatkan jumlah action plan yang eskalasi
     */
    public function getEskalasiActionPlansCountAttribute()
    {
        return $this->actionPlans()->where('status_action_plan', 'Eskalasi')->count();
    }

    /**
     * Accessor untuk mendapatkan persentase completion
     */
    public function getCompletionPercentageAttribute()
    {
        if ($this->jumlah_action_plan == 0) return 0;
        
        return round(($this->done_action_plans_count / $this->jumlah_action_plan) * 100, 1);
    }

    /**
     * Scope untuk pencarian global
     */
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

    /**
     * Scope untuk filter berdasarkan bulan
     */
    public function scopeByMonth($query, $month)
    {
        return $query->whereMonth('tgl', $month);
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('tgl', $year);
    }
}