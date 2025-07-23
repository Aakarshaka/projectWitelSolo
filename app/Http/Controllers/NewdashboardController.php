<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class NewdashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Total Users
        $total_users = User::count();

        // 2. Total Activity Logs (Total Perubahan)
        $total_activity_logs = 0;
        
        // Cek apakah menggunakan spatie/laravel-activitylog
        if (class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            $total_activity_logs = Activity::count();
        } else {
            // Jika ada tabel activity_logs custom
            if (Schema::hasTable('activity_logs')) {
                $total_activity_logs = DB::table('activity_logs')->count();
            }
        }

        return view('dashboard.newdashboard', compact(
            'total_users', 
            'total_activity_logs'
        ));
    }
}