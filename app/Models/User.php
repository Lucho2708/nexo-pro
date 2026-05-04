<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'terms_accepted_at', 'is_active', 'role', 'is_standalone', 'current_copropiedad_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids, TwoFactorAuthenticatable;

    /**
     * Role check helpers.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Get the active/current copropiedad the user is managing or visiting.
     */
    public function currentCopropiedad(): BelongsTo
    {
        return $this->belongsTo(Copropiedad::class, 'current_copropiedad_id');
    }

    public function consents()
    {
        return $this->hasMany(LegalConsent::class);
    }

    /**
     * Get all copropiedades an Admin has access to manage.
     */
    public function managedCopropiedades(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Copropiedad::class, 'admin_copropiedad', 'user_id', 'copropiedad_id')
            ->withTimestamps();
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the units associated with the user (for Owner Portal).
     */
    public function unidades(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Unidad::class, 'unidad_user')
            ->withPivot('role')
            ->withTimestamps();
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
            'is_standalone' => 'boolean',
        ];
    }
}
