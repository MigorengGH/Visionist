<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;


class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasAvatar
{

    public function getFilamentAvatarUrl(): ?string
    {
        // If avatar is null or empty, return null (Filament will use default avatar)
        if (!$this->avatar) {
            return $this->avatar_url;
        }

        return asset('storage/' . $this->avatar);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->isAdmin, // Only admins can access the admin panel
            'freelancer' => !$this->isAdmin, // Only users (non-admins) can access the user panel
            default => false, // Deny access to unknown panels
        };
    }

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'phone_verified_at',
        'isAdmin',
        'aboutme',
        'tags',
        'instagram',
        'youtube',
        'certificate_1',
        'certificate_2',
        'certificate_3',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tags' => 'array',
        'password' => 'hashed',
        'avatar' => 'array',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function certificateApplications()
{
    return $this->hasMany(CertificateApplication::class);
}
public function artworks()
{
    return $this->hasMany(Artwork::class);
}
public function workshops()
{
    return $this->hasMany(Workshop::class);
}

public function makejobs()
{
    return $this->hasMany(Makejob::class);
}
public function applications()
{
    return $this->hasMany(Application::class);
}

public function isAdmin(): bool
{
    return $this->isAdmin;
}

public function hires()
{
    return $this->hasMany(Hire::class, 'freelancer_id');
}


}
