<?php

namespace App\Models;

use App\Models\Setup\SmtpUser;
use App\Models\Setup\SocialCredential;
use App\Traits\CommonTrait;
use Database\Factories\UserFactory;
use EragPermission\Traits\HasPermissionsTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use CommonTrait, HasFactory, HasPermissionsTrait, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'project_id',
        'id',
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

    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? $this->asDateTime($value)->format($this->getDateFormatSettings()) : null;
    }

    public function socialCredentials(): BelongsTo
    {
        return $this->belongsTo(SocialCredential::class);
    }

    public function smtpUser(): BelongsTo
    {
        return $this->belongsTo(SmtpUser::class);
    }

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
}
