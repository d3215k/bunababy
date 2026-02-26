<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use App\Enums\UserType;
use App\Models\Scopes\ActiveScope;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'type' => UserType::class,
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScopes([ActiveScope::class]);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getIsMidwifeAttribute(): bool
    {
        return $this->type === UserType::MIDWIFE;
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->type === UserType::ADMIN || $this->type === UserType::OWNER;
    }

    public function getIsCustomerAttribute(): bool
    {
        return $this->type === UserType::CUSTOMER;
    }

    public function getIsOwnerAttribute(): bool
    {
        return $this->type === UserType::OWNER;
    }

    public function canImpersonate()
    {
        return true;
    }

    public function midwife(): BelongsTo
    {
        return $this->belongsTo(Midwife::class);
    }

    public function scopeOwners($query)
    {
        return $query->where('type', UserType::OWNER);
    }

    public function scopeMidwives($query)
    {
        return $query->where('type', UserType::MIDWIFE);
    }

    public function scopeCustomers($query)
    {
        return $query->where('type', UserType::CUSTOMER);
    }
}
