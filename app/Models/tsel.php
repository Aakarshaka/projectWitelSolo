<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tsel extends Model
{
    protected $guarded = [];

    public function sntelda(): BelongsTo
    {
        return $this->belongsTo(Sntelda::class);
    }

    public function snam(): BelongsTo
    {
        return $this->belongsTo(Snam::class);
    }

    public function snunit(): BelongsTo
    {
        return $this->belongsTo(Snunit::class);
    }
}
