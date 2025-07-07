<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class snam extends Model
{
    protected $guarded = [];

    public function tsel(): HasOne
    {
        return $this->hasOne(Tsel::class);
    }

    public function tifta(): HasOne
    {
        return $this->hasOne(Tifta::class);
    }

    public function treg(): HasOne
    {
        return $this->hasOne(Treg::class);
    }

    public function gsd(): HasOne
    {
        return $this->hasOne(Gsd::class);
    }

    public function witel(): HasOne
    {
        return $this->hasOne(Witel::class);
    }
}
