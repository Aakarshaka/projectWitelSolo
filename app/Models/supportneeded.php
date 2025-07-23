<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Supportneeded extends Model
{
    use HasFactory;

    protected $table = 'supportneededs';

    protected $fillable = [
        'agenda',
        'unit_or_telda',
        'start_date',
        'end_date',
        'off_day',
        'notes_to_follow_up',
        'uic',
        'uic_approvals',
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
        'uic_approvals' => 'json',
    ];

    // Relationship with Newwarroom
    public function warroom()
    {
        return $this->hasOne(Newwarroom::class, 'supportneeded_id');
    }

    // Accessor untuk UIC array
    public function getUicArrayAttribute()
    {
        return $this->uic ? explode(',', $this->uic) : [];
    }

    // Accessor untuk UIC approvals
    public function getUicApprovalsArrayAttribute()
    {
        return $this->uic_approvals ? json_decode($this->uic_approvals, true) : [];
    }

    // Calculate days between start and end date
    public function getCalculatedOffDayAttribute()
    {
        if (!$this->start_date) {
            return 0;
        }

        if ($this->progress === 'Done' && $this->end_date) {
            return Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;
        } else {
            return ceil(Carbon::parse($this->start_date)->diffInHours(Carbon::now()) / 24);
        }
    }

    // Get progress percentage
    public function getProgressPercentageAttribute()
    {
        switch ($this->progress) {
            case 'Open':
                return 0;
            case 'Need Discuss':
                return 25;
            case 'On Progress':
                return 75;
            case 'Done':
                return 100;
            default:
                return 0;
        }
    }

    // Get progress color class
    public function getProgressColorAttribute()
    {
        switch ($this->progress) {
            case 'Open':
                return 'bg-red';
            case 'Need Discuss':
                return 'bg-orange';
            case 'On Progress':
                return 'bg-yellow';
            case 'Done':
                return 'bg-green';
            default:
                return 'bg-gray';
        }
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'Eskalasi':
                return 'status-done';
            case 'Action':
                return 'status-action';
            case 'Support Needed':
                return 'status-in-progress';
            default:
                return 'status-empty';
        }
    }
}