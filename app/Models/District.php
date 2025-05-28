<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected $fillable = [
        'state_id',
        'name',
    ];

    /**
     * Get the state that owns the district.
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get all workshops in this district.
     */
    public function workshops(): HasMany
    {
        return $this->hasMany(Workshop::class);
    }

    public function makejobs(): HasMany
    {
        return $this->hasMany(Makejob::class);
    }
}
