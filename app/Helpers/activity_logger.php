<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('log_activity')) {
    function log_activity($action, $model = null, $description = null, $changes = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $model ? class_basename($model) : null,
            'model_id' => $model->id ?? null,
            'description' => $description,
            'changes' => $changes,
            'ip' => request()->ip(),
        ]);
    }
}
