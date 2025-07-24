<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

    /**
     * Relationship with Newwarroom
     */
    public function warroom()
    {
        return $this->hasOne(Newwarroom::class, 'supportneeded_id');
    }

    /**
     * Boot method untuk menangani event model
     */
    protected static function boot()
    {
        parent::boot();

        // Event listener untuk auto-sync ke warroom setelah create
        static::created(function ($support) {
            try {
                Log::info('Supportneeded created, starting sync process', [
                    'support_id' => $support->id,
                    'status' => $support->status,
                    'uic' => $support->uic,
                    'agenda' => $support->agenda
                ]);

                // Hanya sync jika status adalah Action
                if ($support->status === 'Action') {
                    static::syncToWarroom($support);
                }
            } catch (\Exception $e) {
                Log::error('Error in created event: ' . $e->getMessage(), [
                    'support_id' => $support->id,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        // Event listener untuk auto-sync ke warroom setelah update
        static::updated(function ($support) {
            try {
                $original = $support->getOriginal();
                $oldStatus = $original['status'] ?? null;
                $oldUic = $original['uic'] ?? null;
                $newStatus = $support->status;
                $newUic = $support->uic;

                Log::info('Supportneeded updated, checking for sync', [
                    'support_id' => $support->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'old_uic' => $oldUic,
                    'new_uic' => $newUic,
                    'agenda' => $support->agenda
                ]);

                static::handleWarroomSync($support, $oldStatus);
            } catch (\Exception $e) {
                Log::error('Error in updated event: ' . $e->getMessage(), [
                    'support_id' => $support->id,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        // Event listener untuk hapus warroom saat support dihapus
        static::deleting(function ($support) {
            try {
                Log::info('Supportneeded deleting, removing warroom if exists', [
                    'support_id' => $support->id
                ]);

                static::removeFromWarroom($support);
            } catch (\Exception $e) {
                Log::error('Error in deleting event: ' . $e->getMessage(), [
                    'support_id' => $support->id,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }

    /**
     * Handle warroom sync logic - MAIN SYNC FUNCTION
     */
    private static function handleWarroomSync($support, $oldStatus = null)
    {
        $currentStatus = $support->status;

        Log::info('Handling warroom sync', [
            'support_id' => $support->id,
            'current_status' => $currentStatus,
            'old_status' => $oldStatus,
            'uic' => $support->uic
        ]);

        if ($currentStatus === 'Action') {
            // Status is Action - create or update warroom
            static::syncToWarroom($support);
        } elseif ($oldStatus === 'Action' && $currentStatus !== 'Action') {
            // Status changed from Action to something else - remove from warroom
            static::removeFromWarroom($support);
        }
        // Jika status bukan Action dan sebelumnya juga bukan Action, tidak perlu sync
    }

    /**
     * Sync support to warroom - IMPROVED VERSION
     */
    private static function syncToWarroom($support)
    {
        DB::beginTransaction();

        try {
            Log::info('Starting syncToWarroom from model', [
                'support_id' => $support->id,
                'status' => $support->status,
                'uic' => $support->uic,
                'agenda' => $support->agenda
            ]);

            // Double check status
            if ($support->status !== 'Action') {
                Log::warning('Support status is not Action, skipping sync', [
                    'support_id' => $support->id,
                    'status' => $support->status
                ]);
                DB::rollback();
                return;
            }

            // Check if warroom already exists
            $warroom = Newwarroom::where('supportneeded_id', $support->id)->first();

            // Prepare warroom data - LENGKAP dengan semua field yang diperlukan
            $warroomData = [
                'tgl' => $support->start_date,
                'agenda' => $support->agenda,
                'unit_or_telda' => $support->unit_or_telda,
                'start_date' => $support->start_date,
                'end_date' => $support->end_date,
                'off_day' => $support->off_day ?? 0,
                'notes_to_follow_up' => $support->notes_to_follow_up,
                'uic' => $support->uic, // PENTING: Pastikan UIC tersinkronisasi
                'uic_approvals' => $support->uic_approvals,
                'progress' => $support->progress,
                'complete' => $support->complete ?? static::calculateProgressPercentage($support->progress),
                'status' => $support->status,
                'response_uic' => $support->response_uic,
                'support_needed' => $support->notes_to_follow_up,
                'jumlah_action_plan' => 1,
                'supportneeded_id' => $support->id,
            ];

            if ($warroom) {
                // Update existing warroom dengan semua data terbaru
                $warroom->update($warroomData);
                Log::info('Updated existing warroom from model', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id,
                    'uic_updated' => $support->uic
                ]);
            } else {
                // Create new warroom
                $warroom = Newwarroom::create($warroomData);
                Log::info('Created new warroom from model', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id,
                    'uic_created' => $support->uic
                ]);
            }

            // Handle action plan
            static::handleActionPlan($warroom, $support);

            DB::commit();

            Log::info('Successfully synced to warroom from model', [
                'support_id' => $support->id,
                'warroom_id' => $warroom->id,
                'final_uic' => $warroom->uic
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error syncing to warroom from model: ' . $e->getMessage(), [
                'support_id' => $support->id,
                'trace' => $e->getTraceAsString()
            ]);
            // Tidak throw exception untuk model events agar tidak mengganggu proses utama
        }
    }

    /**
     * Handle action plan creation/update
     */
    private static function handleActionPlan($warroom, $support)
    {
        try {
            // Check if action plan exists
            $existingActionPlan = $warroom->actionPlans()
                ->where('plan_number', 1)
                ->first();

            $actionPlanData = [
                'action_plan' => $support->notes_to_follow_up ?? 'Action plan dari support needed',
                'status_action_plan' => static::mapProgressToActionPlanStatus($support->progress),
            ];

            if ($existingActionPlan) {
                // Update existing action plan
                $existingActionPlan->update($actionPlanData);
                Log::info('Updated existing action plan from model', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id,
                    'action_plan_id' => $existingActionPlan->id,
                    'status' => $actionPlanData['status_action_plan']
                ]);
            } else {
                // Create new action plan
                $actionPlanData['newwarroom_id'] = $warroom->id;
                $actionPlanData['plan_number'] = 1;

                $newActionPlan = ActionPlan::create($actionPlanData);
                Log::info('Created new action plan from model', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id,
                    'action_plan_id' => $newActionPlan->id,
                    'status' => $actionPlanData['status_action_plan']
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error handling action plan from model: ' . $e->getMessage(), [
                'support_id' => $support->id,
                'warroom_id' => $warroom->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Remove support from warroom - IMPROVED VERSION
     */
    private static function removeFromWarroom($support)
    {
        DB::beginTransaction();

        try {
            Log::info('Starting removeFromWarroom from model', [
                'support_id' => $support->id
            ]);

            $warroom = Newwarroom::where('supportneeded_id', $support->id)->first();

            if ($warroom) {
                // Delete action plans first
                $deletedActionPlans = $warroom->actionPlans()->delete();

                // Delete warroom
                $warroomId = $warroom->id;
                $warroom->delete();

                Log::info('Successfully removed warroom and action plans from model', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroomId,
                    'deleted_action_plans' => $deletedActionPlans
                ]);
            } else {
                Log::info('No warroom found to remove from model', [
                    'support_id' => $support->id
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error removing from warroom from model: ' . $e->getMessage(), [
                'support_id' => $support->id,
                'trace' => $e->getTraceAsString()
            ]);
            // Tidak throw exception untuk model events
        }
    }

    /**
     * Map Supportneeded progress to ActionPlan status
     */
    private static function mapProgressToActionPlanStatus($progress)
    {
        switch ($progress) {
            case 'Open':
                return 'Open';
            case 'Need Discuss':
                return 'Need Discuss';
            case 'On Progress':
                return 'Progress';
            case 'Done':
                return 'Done';
            default:
                return 'Open';
        }
    }

    /**
     * Manual sync method untuk testing/debugging
     */
    public function syncToWarroomManual()
    {
        Log::info('Manual sync triggered', [
            'support_id' => $this->id,
            'status' => $this->status
        ]);

        return static::handleWarroomSync($this);
    }

    /**
     * Check if this support should have a warroom
     */
    public function shouldHaveWarroom()
    {
        return $this->status === 'Action';
    }

    /**
     * Verify sync status
     */
    public function verifySyncStatus()
    {
        $shouldHaveWarroom = $this->shouldHaveWarroom();
        $hasWarroom = $this->warroom !== null;

        return [
            'support_id' => $this->id,
            'status' => $this->status,
            'should_have_warroom' => $shouldHaveWarroom,
            'has_warroom' => $hasWarroom,
            'is_synced' => $shouldHaveWarroom === $hasWarroom,
            'warroom_id' => $hasWarroom ? $this->warroom->id : null
        ];
    }

    // ==== EXISTING METHODS - TIDAK DIUBAH ====

    /**
     * Accessor untuk UIC array
     */
    public function getUicArrayAttribute()
    {
        return $this->uic ? explode(',', $this->uic) : [];
    }

    /**
     * Accessor untuk UIC approvals
     */
    public function getUicApprovalsArrayAttribute()
    {
        return $this->uic_approvals ? json_decode($this->uic_approvals, true) : [];
    }

    /**
     * Calculate days between start and end date
     */
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

    /**
     * Get progress percentage
     */
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

    /**
     * Get progress color class
     */
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

    /**
     * Get status badge class
     */
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

    /**
     * Scope untuk search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $searchTerm = '%' . $search . '%';
            $q->where('agenda', 'like', $searchTerm)
                ->orWhere('unit_or_telda', 'like', $searchTerm)
                ->orWhere('notes_to_follow_up', 'like', $searchTerm)
                ->orWhere('uic', 'like', $searchTerm)
                ->orWhere('progress', 'like', $searchTerm)
                ->orWhere('status', 'like', $searchTerm)
                ->orWhere('response_uic', 'like', $searchTerm);
        });
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan progress
     */
    public function scopeByProgress($query, $progress)
    {
        return $query->where('progress', $progress);
    }
}