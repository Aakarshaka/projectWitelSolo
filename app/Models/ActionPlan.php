<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionPlan extends Model
{
    protected $fillable = [
        'newwarroom_id',
        'plan_number',
        'action_plan',
        'update_action_plan',
        'status_action_plan',
    ];

    protected $casts = [
        'plan_number' => 'integer',
    ];

    /**
     * Relationship dengan Newwarroom
     */
    public function newwarroom(): BelongsTo
    {
        return $this->belongsTo(Newwarroom::class, 'newwarroom_id');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_action_plan', $status);
    }

    /**
     * Accessor untuk mendapatkan status dengan warna
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status_action_plan) {
            'Open' => 'secondary',
            'Progress' => 'primary',
            'Need Discuss' => 'warning',
            'Eskalasi' => 'danger',
            'Done' => 'success',
            default => 'light'
        };
    }
}