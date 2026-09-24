<?php

namespace App\Models;

use Database\Factories\SaleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $listing_id
 * @property int $moderator_id
 * @property string $listed_price
 * @property string $actual_price
 * @property Carbon $sold_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Listing $listing
 * @property-read User $moderator
 */
#[Fillable([
    'listing_id',
    'moderator_id',
    'listed_price',
    'actual_price',
    'sold_at',
    'notes',
])]
class Sale extends Model
{
    /** @use HasFactory<SaleFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'listed_price' => 'decimal:2',
            'actual_price' => 'decimal:2',
            'sold_at' => 'datetime',
        ];
    }

    /**
     * The sold listing (with trashed fallback to preserve financial history).
     *
     * @return BelongsTo<Listing, $this>
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class)->withTrashed();
    }

    /**
     * The moderator who closed this sale.
     *
     * @return BelongsTo<User, $this>
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    /**
     * Activity log records related to this sale.
     *
     * @return MorphMany<ActivityLog, $this>
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /**
     * Scope query to sales attributed to a specific moderator.
     *
     * @param  Builder<Sale>  $query
     * @return Builder<Sale>
     */
    public function scopeForModerator(Builder $query, int|User $user): Builder
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $query->where('moderator_id', $userId);
    }

    /**
     * Scope query to sales within a specific date range.
     *
     * @param  Builder<Sale>  $query
     * @return Builder<Sale>
     */
    public function scopeBetweenDates(Builder $query, mixed $from, mixed $to): Builder
    {
        return $query->whereBetween('sold_at', [$from, $to]);
    }

    /**
     * Scope query to sales in a specific calendar month.
     *
     * @param  Builder<Sale>  $query
     * @return Builder<Sale>
     */
    public function scopeInMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->whereYear('sold_at', $year)
            ->whereMonth('sold_at', $month);
    }
}
