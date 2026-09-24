<?php

namespace App\Models;

use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $body
 * @property Carbon|null $read_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $sender
 * @property-read User $receiver
 */
#[Fillable([
    'sender_id',
    'receiver_id',
    'body',
    'read_at',
])]
class Message extends Model
{
    /** @use HasFactory<MessageFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    /**
     * The user who sent this message.
     *
     * @return BelongsTo<User, $this>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * The user who received this message.
     *
     * @return BelongsTo<User, $this>
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Check if the message has been read by the recipient.
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Mark this message as read.
     */
    public function markAsRead(): void
    {
        if (! $this->isRead()) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Scope query to all messages between two users (conversation thread).
     *
     * @param  Builder<Message>  $query
     * @return Builder<Message>
     */
    public function scopeBetween(Builder $query, int|User $userOne, int|User $userTwo): Builder
    {
        $id1 = $userOne instanceof User ? $userOne->id : $userOne;
        $id2 = $userTwo instanceof User ? $userTwo->id : $userTwo;

        return $query->where(function (Builder $subQuery) use ($id1, $id2) {
            $subQuery->where(function (Builder $q) use ($id1, $id2) {
                $q->where('sender_id', $id1)->where('receiver_id', $id2);
            })->orWhere(function (Builder $q) use ($id1, $id2) {
                $q->where('sender_id', $id2)->where('receiver_id', $id1);
            });
        });
    }

    /**
     * Scope query to unread messages.
     *
     * @param  Builder<Message>  $query
     * @return Builder<Message>
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope query to messages received by a specific user.
     *
     * @param  Builder<Message>  $query
     * @return Builder<Message>
     */
    public function scopeReceivedBy(Builder $query, int|User $user): Builder
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $query->where('receiver_id', $userId);
    }
}
