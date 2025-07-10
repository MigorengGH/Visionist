<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Artwork extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'media',
        'tags',
        'user_id',
        'publish',
    ];

    protected $casts = [
        'image' => 'array',
        'tags' => 'array',
        'publish' => 'boolean',
    ];

    protected $withCount = ['likes'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artwork) {
            if (!$artwork->user_id && Auth::check()) {
                $artwork->user_id = Auth::id();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function toggleLike(User $user)
    {
        if ($this->isLikedBy($user)) {
            $this->likes()->where('user_id', $user->id)->delete();
            return false;
        }

        $this->likes()->create(['user_id' => $user->id]);
        return true;
    }

    public function getImageUrlAttribute()
    {
        if (is_array($this->image) && !empty($this->image)) {
            return $this->image[0];
        }

        if (is_string($this->image) && !empty($this->image)) {
            return $this->image;
        }

        return null;
    }
}
