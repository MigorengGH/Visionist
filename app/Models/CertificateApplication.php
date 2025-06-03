<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateApplication extends Model
{

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'cv_path',
        'status',
        'admin_comment',
        'reapply_count',
        'approved_by',];

        protected $casts = [
            'cv_path' => 'array',
        ];

    /**
     * Define the relationship between CertificateApplication and User.
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * Define the relationship between CertificateApplication and User.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class,'approved_by','id');
    }
}
