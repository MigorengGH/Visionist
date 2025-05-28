<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Searchjob extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'budget',
        'status',
        'user_id',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'searchjob_id');
    }
}
