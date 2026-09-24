<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $username
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $phone
 * @property string|null $whatsapp_number
 * @property bool $is_active
 * @property Carbon|null $last_login_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'username', 'password', 'role', 'phone', 'whatsapp_number', 'is_active', 'last_login_at'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a moderator.
     */
    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    /**
     * Listings currently assigned to this user.
     *
     * @return HasMany<Listing, $this>
     */
    public function assignedListings(): HasMany
    {
        return $this->hasMany(Listing::class, 'assigned_to');
    }

    /**
     * Listings originally created by this user.
     *
     * @return HasMany<Listing, $this>
     */
    public function createdListings(): HasMany
    {
        return $this->hasMany(Listing::class, 'created_by');
    }

    /**
     * Sales recorded by this moderator.
     *
     * @return HasMany<Sale, $this>
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'moderator_id');
    }

    /**
     * Todos assigned to this moderator.
     *
     * @return HasMany<Todo, $this>
     */
    public function assignedTodos(): HasMany
    {
        return $this->hasMany(Todo::class, 'assigned_to');
    }

    /**
     * Todos created by this user.
     *
     * @return HasMany<Todo, $this>
     */
    public function createdTodos(): HasMany
    {
        return $this->hasMany(Todo::class, 'created_by');
    }

    /**
     * Direct messages sent by this user.
     *
     * @return HasMany<Message, $this>
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Direct messages received by this user.
     *
     * @return HasMany<Message, $this>
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Notices available to this user (via pivot table with read receipt).
     *
     * @return BelongsToMany<Notice, $this>
     */
    public function notices(): BelongsToMany
    {
        return $this->belongsToMany(Notice::class)
            ->withPivot('read_at')
            ->withTimestamps();
    }

    /**
     * Notices created by this user (Admin).
     *
     * @return HasMany<Notice, $this>
     */
    public function createdNotices(): HasMany
    {
        return $this->hasMany(Notice::class, 'created_by');
    }

    /**
     * Activity log entries performed by this user.
     *
     * @return HasMany<ActivityLog, $this>
     */
    public function activities(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'actor_id');
    }

    /**
     * Scope query to only active users.
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query to only moderators.
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeModerators(Builder $query): Builder
    {
        return $query->where('role', 'moderator');
    }

    /**
     * Scope query to only admins.
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', 'admin');
    }
}
