<?php

namespace App\Models;

use Database\Factories\TodoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $assigned_to
 * @property int $created_by
 * @property string $title
 * @property string|null $description
 * @property string $priority
 * @property string $status
 * @property Carbon|null $due_date
 * @property Carbon|null $completed_at
 * @property int|null $completed_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $assignee
 * @property-read User $creator
 * @property-read User|null $completer
 */
#[Fillable([
    'assigned_to',
    'created_by',
    'title',
    'description',
    'priority',
    'status',
    'due_date',
    'completed_at',
    'completed_by',
])]
class Todo extends Model
{
    /** @use HasFactory<TodoFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * The moderator assigned to complete this todo.
     *
     * @return BelongsTo<User, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * The admin user who created this todo.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The user who marked this todo complete.
     *
     * @return BelongsTo<User, $this>
     */
    public function completer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Check if the task is still open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if the task is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the task is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->isOpen()
            && $this->due_date !== null
            && $this->due_date->isBefore(now()->startOfDay());
    }

    /**
     * Mark the todo as completed.
     */
    public function markCompleted(?User $user = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by' => $user?->id ?? auth()->id(),
        ]);
    }

    /**
     * Reopen a completed todo.
     */
    public function reopen(): void
    {
        $this->update([
            'status' => 'open',
            'completed_at' => null,
            'completed_by' => null,
        ]);
    }

    /**
     * Scope query to open todos.
     *
     * @param  Builder<Todo>  $query
     * @return Builder<Todo>
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope query to completed todos.
     *
     * @param  Builder<Todo>  $query
     * @return Builder<Todo>
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope query to overdue todos.
     *
     * @param  Builder<Todo>  $query
     * @return Builder<Todo>
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', 'open')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->startOfDay());
    }

    /**
     * Scope query to todos assigned to a specific user.
     *
     * @param  Builder<Todo>  $query
     * @return Builder<Todo>
     */
    public function scopeForAssignee(Builder $query, int|User $user): Builder
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $query->where('assigned_to', $userId);
    }
}
