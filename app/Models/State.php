<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class State extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get all districts in this state.
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    /**
     * Get all workshops in this state.
     */
    public function workshops(): HasManyThrough
    {
        return $this->hasManyThrough(Workshop::class, District::class);
    }

    public function makejobs(): HasMany
    {
        return $this->hasMany(Makejob::class);
    }
}
