<?php

namespace App\Models;

use Database\Factories\NoticeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $created_by
 * @property string $title
 * @property string $body
 * @property string $priority
 * @property string $audience_type
 * @property Carbon|null $expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $author
 */
#[Fillable([
    'created_by',
    'title',
    'body',
    'priority',
    'audience_type',
    'expires_at',
])]
class Notice extends Model
{
    /** @use HasFactory<NoticeFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /**
     * The admin user who created this notice.
     *
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The targeted moderators / users who received or read this notice.
     *
     * @return BelongsToMany<User, $this>
     */
    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('read_at')
            ->withTimestamps();
    }

    /**
     * Check if the notice is marked as important.
     */
    public function isImportant(): bool
    {
        return $this->priority === 'important';
    }

    /**
     * Check if the notice has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Check if this notice has been read by the given user.
     */
    public function isReadBy(User $user): bool
    {
        return $this->recipients()
            ->where('users.id', $user->id)
            ->whereNotNull('notice_user.read_at')
            ->exists();
    }

    /**
     * Mark this notice as read for the given user.
     */
    public function markAsReadFor(User $user): void
    {
        $existing = $this->recipients()->where('users.id', $user->id)->first();

        if ($existing) {
            $this->recipients()->updateExistingPivot($user->id, [
                'read_at' => now(),
            ]);
        } else {
            $this->recipients()->attach($user->id, [
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Scope query to non-expired notices.
     *
     * @param  Builder<Notice>  $query
     * @return Builder<Notice>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $subQuery) {
            $subQuery->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope query to important notices.
     *
     * @param  Builder<Notice>  $query
     * @return Builder<Notice>
     */
    public function scopeImportant(Builder $query): Builder
    {
        return $query->where('priority', 'important');
    }

    /**
     * Scope query to notices visible to a specific user.
     *
     * @param  Builder<Notice>  $query
     * @return Builder<Notice>
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where(function (Builder $subQuery) use ($user) {
            $subQuery->where('audience_type', 'all')
                ->orWhereHas('recipients', function (Builder $recipientQuery) use ($user) {
                    $recipientQuery->where('users.id', $user->id);
                });
        });
    }
}
