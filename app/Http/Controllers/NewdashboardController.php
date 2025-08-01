<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class NewdashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get active users (users who currently have active sessions)
        $active_user_ids = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes(5)->timestamp) // Active dalam 5 menit terakhir
            ->pluck('user_id')
            ->unique();

        $active_users = User::select('id', 'name', 'email', 'role', 'updated_at')
            ->whereIn('id', $active_user_ids)
            ->orderBy('name', 'asc')
            ->get();

        // Get inactive users (users who don't have active sessions)
        $inactive_users = User::select('id', 'name', 'email', 'role', 'updated_at')
            ->whereNotIn('id', $active_user_ids)
            ->orderBy('name', 'asc')
            ->get();

        // Count totals
        $total_active = $active_users->count();
        $total_inactive = $inactive_users->count();
        $total_users = $total_active + $total_inactive;

        return view('dashboard.newdashboard', compact(
            'active_users',
            'inactive_users', 
            'total_active',
            'total_inactive',
            'total_users'
        ));
    }
}