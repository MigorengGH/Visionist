<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;


class Workshop extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'state_id',
        'district_id',
        'price',
        'image',
        'tags',
        'publish',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'image' => 'array',
        'tags' => 'array',
        'publish' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($workshop) {
            if ($workshop->image) {
                foreach ($workshop->image as $file) {
                    Storage::disk('public')->delete("WorkshopImage/{$file}");
                }
            }
        });
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
