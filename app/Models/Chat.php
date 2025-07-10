<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'user_one_id',
        'user_two_id',
    ];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public static function between($userA, $userB)
    {
        return static::where(function ($query) use ($userA, $userB) {
            $query->where('user_one_id', $userA)->where('user_two_id', $userB);
        })->orWhere(function ($query) use ($userA, $userB) {
            $query->where('user_one_id', $userB)->where('user_two_id', $userA);
        });
    }
}
