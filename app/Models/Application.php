<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'makejob_id',
        'proposed_price',
        'cover_letter',
        'supporting_documents',
        'status',
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function makejob()
    {
        return $this->belongsTo(Makejob::class);
    }
}
