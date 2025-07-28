<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    // Progress mapping untuk konsistensi
    const PROGRESS_MAP = [
        'Open' => 0,
        'Need Discuss' => 25,
        'On Progress' => 75,
        'Done' => 100,
    ];

    // UIC categories untuk status determination<option value
    const ESCALATION_UICS = ['RLEGS', 'RSO REGIONAL', 'ED', 'TIF', 'TSEL', 'GSD', 'RSMES', 'BPPLP', 'SSS','RWS'];
    const SUPPORT_NEEDED_UICS = ['TELDA BLORA','TELDA BOYOLALI','TELDA JEPARA','TELDA KLATEN','TELDA KUDUS','MEA SOLO','TELDA PATI','TELDA PURWODADI','TELDA REMBANG','TELDA SRAGEN','TELDA WONOGIRI','BS', 'GS', 'RSO WITEL', 'SSGS', 'PRQ', 'LESA V'];

    /**
     * Boot method untuk auto-calculate fields
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->calculateStatus();
            $model->calculateOffDay();
            $model->calculateComplete();
            $model->handleEndDate();
        });
    }

    /**
     * Calculate status berdasarkan unit_or_telda dan uic
     */
    private function calculateStatus()
    {
        if (empty($this->unit_or_telda) || empty($this->uic)) {
            $this->status = '';
        } elseif ($this->unit_or_telda === $this->uic) {
            $this->status = 'Action';
        } elseif (in_array($this->uic, self::ESCALATION_UICS)) {
            $this->status = 'Eskalasi';
        } elseif (in_array($this->uic, self::SUPPORT_NEEDED_UICS)) {
            $this->status = 'Support Needed';
        } else {
            $this->status = '';
        }
    }

    /**
     * Calculate off_day berdasarkan start_date dan progress
     */
    private function calculateOffDay()
    {
        if (!$this->start_date) {
            $this->off_day = 0;
            return;
        }

        $startDate = Carbon::parse($this->start_date);
        
        if ($this->progress === 'Done' && $this->end_date) {
            $endDate = Carbon::parse($this->end_date);
            $this->off_day = $startDate->diffInDays($endDate) + 1;
        } else {
            $this->off_day = ceil($startDate->diffInHours(Carbon::now()) / 24);
        }
    }

    /**
     * Calculate complete percentage berdasarkan progress
     */
    private function calculateComplete()
    {
        $this->complete = self::PROGRESS_MAP[$this->progress] ?? 0;
    }

    /**
     * Handle end_date berdasarkan progress
     */
    private function handleEndDate()
    {
        if ($this->progress === 'Done' && $this->getOriginal('progress') !== 'Done') {
            $this->end_date = Carbon::now()->format('Y-m-d');
        } elseif ($this->progress !== 'Done' && $this->getOriginal('progress') === 'Done') {
            $this->end_date = null;
        }
    }

    /**
     * Relasi ke model Newwarroom
     */
    public function warrooms()
    {
        return $this->hasMany(Newwarroom::class, 'supportneeded_id');
    }

    /**
     * Relasi ke warroom yang aktif
     */
    public function activeWarroom()
    {
        return $this->hasOne(Newwarroom::class, 'supportneeded_id');
    }

    /**
     * Scope untuk filter berdasarkan search term
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('agenda', 'like', '%' . $search . '%')
                ->orWhere('unit_or_telda', 'like', '%' . $search . '%')
                ->orWhere('notes_to_follow_up', 'like', '%' . $search . '%')
                ->orWhere('uic', 'like', '%' . $search . '%')
                ->orWhere('progress', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ->orWhere('response_uic', 'like', '%' . $search . '%');
        });
    }
}