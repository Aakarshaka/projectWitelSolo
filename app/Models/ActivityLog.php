<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id',
        'description', 'changes', 'ip',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

