<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $phone
 * @property string|null $position
 * @property string|null $address
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Household> $households
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Household> $verifiedHouseholds
 * @property-read int|null $households_count
 * @property-read int|null $verified_households_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User pengisiData()
 * @method static \Illuminate\Database\Eloquent\Builder|User verifikator()
 * @method static \Illuminate\Database\Eloquent\Builder|User kepalaDinas()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'position',
        'address',
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

    /**
     * Get the households created by this user.
     */
    public function households(): HasMany
    {
        return $this->hasMany(Household::class, 'created_by');
    }

    /**
     * Get the households verified by this user.
     */
    public function verifiedHouseholds(): HasMany
    {
        return $this->hasMany(Household::class, 'verified_by');
    }

    /**
     * Scope a query to only include data entry users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePengisiData($query)
    {
        return $query->where('role', 'pengisi_data');
    }

    /**
     * Scope a query to only include verifier users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerifikator($query)
    {
        return $query->where('role', 'verifikator');
    }

    /**
     * Scope a query to only include head of office users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKepalaDinas($query)
    {
        return $query->where('role', 'kepala_dinas');
    }

    /**
     * Check if user can input data.
     *
     * @return bool
     */
    public function canInputData(): bool
    {
        return in_array($this->role, ['pengisi_data', 'verifikator']);
    }

    /**
     * Check if user can verify data.
     *
     * @return bool
     */
    public function canVerifyData(): bool
    {
        return in_array($this->role, ['verifikator', 'kepala_dinas']);
    }

    /**
     * Check if user can view reports.
     *
     * @return bool
     */
    public function canViewReports(): bool
    {
        return $this->role === 'kepala_dinas';
    }

    /**
     * Get role label in Indonesian.
     *
     * @return string
     */
    public function getRoleLabel(): string
    {
        $labels = [
            'pengisi_data' => 'Pengisi Data',
            'verifikator' => 'Verifikator',
            'kepala_dinas' => 'Kepala Dinas',
        ];

        return $labels[$this->role] ?? $this->role;
    }
}