<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Newwarroom;
use App\Models\ActionPlan;
use Illuminate\Support\Facades\DB;

class ActionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        
        try {
            // Ambil data warroom yang sudah ada
            $warroomData = Newwarroom::all();
            
            foreach ($warroomData as $warroom) {
                // Jika jumlah_action_plan masih 0, set ke 1
                if ($warroom->jumlah_action_plan == 0) {
                    $warroom->update(['jumlah_action_plan' => 1]);
                }
                
                // Buat action plans berdasarkan jumlah_action_plan
                for ($i = 1; $i <= $warroom->jumlah_action_plan; $i++) {
                    ActionPlan::create([
                        'newwarroom_id' => $warroom->id,
                        'plan_number' => $i,
                        'action_plan' => "Action plan {$i} untuk agenda {$warroom->agenda}",
                        'update_action_plan' => null,
                        'status_action_plan' => $this->getRandomStatus(),
                    ]);
                }
            }
            
            DB::commit();
            
            $this->command->info('Action Plans seeder completed successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('Action Plans seeder failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Get random status for demo data
     */
    private function getRandomStatus(): string
    {
        $statuses = ['Open', 'Progress', 'Need Discuss', 'Done'];
        return $statuses[array_rand($statuses)];
    }
}