<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Witel extends Model
{
    protected $guarded = [];

    protected $table = 'witel';

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
