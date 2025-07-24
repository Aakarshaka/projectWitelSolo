<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Supportneeded;
use App\Models\Newwarroom;
use App\Models\ActionPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixSupportneededWarroomSync extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'supportneeded:fix-sync {--dry-run : Show what would be changed without making changes} {--force : Force sync without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Fix synchronization between supportneeded and warroom tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Analyzing Supportneeded-Warroom synchronization...');
        
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        // Get all supportneeded records
        $allSupports = Supportneeded::with('warroom')->get();
        
        $stats = [
            'total_supports' => $allSupports->count(),
            'action_status' => $allSupports->where('status', 'Action')->count(),
            'has_warroom' => $allSupports->filter(fn($s) => $s->warroom !== null)->count(),
            'needs_sync' => 0,
            'needs_removal' => 0,
            'already_synced' => 0
        ];

        $toSync = collect();
        $toRemove = collect();
        $synced = collect();

        // Analyze each support record
        foreach ($allSupports as $support) {
            $shouldHaveWarroom = ($support->status === 'Action');
            $hasWarroom = ($support->warroom !== null);

            if ($shouldHaveWarroom && !$hasWarroom) {
                // Needs warroom but doesn't have one
                $toSync->push($support);
                $stats['needs_sync']++;
            } elseif (!$shouldHaveWarroom && $hasWarroom) {
                // Has warroom but shouldn't
                $toRemove->push($support);
                $stats['needs_removal']++;
            } elseif ($shouldHaveWarroom && $hasWarroom) {
                // Correctly synced
                $synced->push($support);
                $stats['already_synced']++;
            }
        }

        // Display analysis results
        $this->displayAnalysis($stats, $toSync, $toRemove);

        if ($toSync->isEmpty() && $toRemove->isEmpty()) {
            $this->info('✅ All records are properly synchronized!');
            return Command::SUCCESS;
        }

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
            return Command::SUCCESS;
        }

        // Ask for confirmation unless forced
        if (!$force) {
            if (!$this->confirm('Do you want to proceed with the synchronization fixes?')) {
                $this->info('❌ Operation cancelled.');
                return Command::FAILURE;
            }
        }

        // Perform the fixes
        $this->performSync($toSync, $toRemove);

        return Command::SUCCESS;
    }

    /**
     * Display analysis results
     */
    private function displayAnalysis($stats, $toSync, $toRemove)
    {
        $this->info('📊 ANALYSIS RESULTS:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Supportneeded Records', $stats['total_supports']],
                ['With Action Status', $stats['action_status']],
                ['Has Warroom', $stats['has_warroom']],
                ['Already Synced Correctly', $stats['already_synced']],
                ['Needs Warroom Creation', $stats['needs_sync']],
                ['Needs Warroom Removal', $stats['needs_removal']],
            ]
        );

        if ($toSync->isNotEmpty()) {
            $this->warn('🔧 RECORDS THAT NEED WARROOM CREATION:');
            $syncData = $toSync->map(function($support) {
                return [
                    'ID' => $support->id,
                    'Agenda' => substr($support->agenda, 0, 50) . '...',
                    'Status' => $support->status,
                    'UIC' => $support->uic ?? '-'
                ];
            });
            $this->table(['ID', 'Agenda', 'Status', 'UIC'], $syncData->toArray());
        }

        if ($toRemove->isNotEmpty()) {
            $this->warn('🗑️  RECORDS THAT NEED WARROOM REMOVAL:');
            $removeData = $toRemove->map(function($support) {
                return [
                    'Support ID' => $support->id,
                    'Warroom ID' => $support->warroom->id,
                    'Agenda' => substr($support->agenda, 0, 50) . '...',
                    'Status' => $support->status
                ];
            });
            $this->table(['Support ID', 'Warroom ID', 'Agenda', 'Status'], $removeData->toArray());
        }
    }

    /**
     * Perform the synchronization fixes
     */
    private function performSync($toSync, $toRemove)
    {
        $this->info('🔧 Starting synchronization fixes...');

        DB::beginTransaction();
        
        try {
            $syncedCount = 0;
            $removedCount = 0;

            // Create missing warrooms
            if ($toSync->isNotEmpty()) {
                $this->info('📝 Creating missing warroom records...');
                $bar = $this->output->createProgressBar($toSync->count());
                $bar->start();

                foreach ($toSync as $support) {
                    $this->createWarroomForSupport($support);
                    $syncedCount++;
                    $bar->advance();
                }
                $bar->finish();
                $this->newLine();
            }

            // Remove unnecessary warrooms
            if ($toRemove->isNotEmpty()) {
                $this->info('🗑️  Removing unnecessary warroom records...');
                $bar = $this->output->createProgressBar($toRemove->count());
                $bar->start();

                foreach ($toRemove as $support) {
                    $this->removeWarroomForSupport($support);
                    $removedCount++;
                    $bar->advance();
                }
                $bar->finish();
                $this->newLine();
            }

            DB::commit();

            $this->info("✅ Synchronization completed successfully!");
            $this->info("📊 Summary:");
            $this->info("   - Created warrooms: {$syncedCount}");
            $this->info("   - Removed warrooms: {$removedCount}");

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('❌ Error during synchronization: ' . $e->getMessage());
            $this->error('Transaction rolled back.');
            Log::error('FixSupportneededWarroomSync failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Create warroom for support record
     */
    private function createWarroomForSupport($support)
    {
        try {
            // Create warroom
            $warroomData = [
                'supportneeded_id' => $support->id,
                'tgl' => $support->start_date,
                'agenda' => $support->agenda,
                'unit_or_telda' => $support->unit_or_telda,
                'start_date' => $support->start_date,
                'end_date' => $support->end_date,
                'off_day' => $support->off_day,
                'notes_to_follow_up' => $support->notes_to_follow_up,
                'uic' => $support->uic,
                'uic_approvals' => $support->uic_approvals,
                'progress' => $support->progress,
                'complete' => $support->complete,
                'status' => $support->status,
                'response_uic' => $support->response_uic,
                'support_needed' => $support->notes_to_follow_up,
                'jumlah_action_plan' => 1,
            ];

            $warroom = Newwarroom::create($warroomData);

            // Create action plan
            ActionPlan::create([
                'newwarroom_id' => $warroom->id,
                'plan_number' => 1,
                'action_plan' => $support->notes_to_follow_up ?? 'Action plan dari support needed',
                'status_action_plan' => $this->mapProgressToActionPlanStatus($support->progress),
            ]);

            Log::info('Created warroom via command', [
                'support_id' => $support->id,
                'warroom_id' => $warroom->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating warroom via command: ' . $e->getMessage(), [
                'support_id' => $support->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Remove warroom for support record
     */
    private function removeWarroomForSupport($support)
    {
        try {
            if ($support->warroom) {
                // Delete action plans first
                $support->warroom->actionPlans()->delete();
                
                // Delete warroom
                $warroomId = $support->warroom->id;
                $support->warroom->delete();

                Log::info('Removed warroom via command', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroomId
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error removing warroom via command: ' . $e->getMessage(), [
                'support_id' => $support->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Map progress to action plan status
     */
    private function mapProgressToActionPlanStatus($progress)
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
}

// TAMBAHAN: Command untuk monitoring sync status
class CheckSupportneededSync extends Command
{
    protected $signature = 'supportneeded:check-sync';
    protected $description = 'Check synchronization status between supportneeded and warroom';

    public function handle()
    {
        $this->info('🔍 Checking Supportneeded-Warroom synchronization status...');

        $supports = Supportneeded::with('warroom')->get();
        
        $syncStatus = $supports->map(function($support) {
            return $support->verifySyncStatus();
        });

        $synced = $syncStatus->where('is_synced', true)->count();
        $notSynced = $syncStatus->where('is_synced', false)->count();

        $this->info("📊 SYNC STATUS SUMMARY:");
        $this->info("   ✅ Properly synced: {$synced}");
        $this->info("   ❌ Not synced: {$notSynced}");
        $this->info("   📋 Total records: " . $supports->count());

        if ($notSynced > 0) {
            $this->warn("\n🚨 Found {$notSynced} records that are not properly synced!");
            $this->info("Run 'php artisan supportneeded:fix-sync' to fix them.");
            
            // Show details of not synced records
            $notSyncedRecords = $syncStatus->where('is_synced', false);
            $this->table(
                ['Support ID', 'Status', 'Should Have Warroom', 'Has Warroom', 'Warroom ID'],
                $notSyncedRecords->map(function($record) {
                    return [
                        $record['support_id'],
                        $record['status'],
                        $record['should_have_warroom'] ? 'Yes' : 'No',
                        $record['has_warroom'] ? 'Yes' : 'No',
                        $record['warroom_id'] ?? '-'
                    ];
                })->toArray()
            );
        } else {
            $this->info("✅ All records are properly synchronized!");
        }

        return Command::SUCCESS;
    }
}