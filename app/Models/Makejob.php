<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Makejob extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'budget',
        'state_id',
        'district_id',
        'user_id',
        'status',
        'is_online',
        'location_type',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_online' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->is_online) {
                $model->state_id = null;
                $model->district_id = null;
                $model->location_type = 'online';
            } else {
                $model->location_type = 'physical';
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
