<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tsel extends Model
{
    //
    protected $guarded = [];

    public function snam(): HasMany
    {
        return $this->HasMany(snam::class);
    }

    public function sntelda(): HasMany
    {
        return $this->HasMany(sntelda::class);
    }

    public function tifta(): HasMany
    {
        return $this->HasMany(tifta::class);
    }

    public function treg(): HasMany
    {
        return $this->HasMany(treg::class);
    }

    public function snunit(): HasMany
    {
        return $this->HasMany(tsel::class);
    }

    public function gsd(): HasMany
    {
        return $this->HasMany(gsd::class);
    }
}
