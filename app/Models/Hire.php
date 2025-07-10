<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hire extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'state_id',
        'district_id',
        'is_online',
        'client_id',
        'freelancer_id',
        'status', // pending, accepted, rejected
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Makejob::class, 'makejob_id');
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function getDisplayStateAttribute()
    {
        return $this->is_online ? '-' : ($this->state?->name ?: '-');
    }

    public function getDisplayDistrictAttribute()
    {
        return $this->is_online ? '-' : ($this->district?->name ?: '-');
    }
}
