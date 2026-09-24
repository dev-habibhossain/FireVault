<?php

namespace App\Models;

use Database\Factories\ListingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string|null $slug
 * @property string $uid
 * @property int $level
 * @property string $account_age
 * @property string $price
 * @property string|null $description
 * @property int $character_count
 * @property int $gun_skin_count
 * @property int $elite_pass_count
 * @property int $rare_item_count
 * @property string $status
 * @property bool $is_featured
 * @property int $assigned_to
 * @property int $created_by
 * @property Carbon|null $sold_at
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $moderator
 * @property-read User $creator
 * @property-read Sale|null $sale
 */
#[Fillable([
    'title',
    'slug',
    'uid',
    'level',
    'account_age',
    'price',
    'description',
    'character_count',
    'gun_skin_count',
    'elite_pass_count',
    'rare_item_count',
    'status',
    'is_featured',
    'assigned_to',
    'created_by',
    'sold_at',
])]
class Listing extends Model
{
    /** @use HasFactory<ListingFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'price' => 'decimal:2',
            'character_count' => 'integer',
            'gun_skin_count' => 'integer',
            'elite_pass_count' => 'integer',
            'rare_item_count' => 'integer',
            'is_featured' => 'boolean',
            'sold_at' => 'datetime',
        ];
    }

    /**
     * The assigned moderator responsible for handling buyer inquiries and sale closing.
     *
     * @return BelongsTo<User, $this>
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * The user who originally created this listing.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * All screenshots/images for this listing, ordered by sort_order.
     *
     * @return HasMany<ListingImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class)->orderBy('sort_order');
    }

    /**
     * The cover image for this listing.
     *
     * @return HasOne<ListingImage, $this>
     */
    public function coverImage(): HasOne
    {
        return $this->hasOne(ListingImage::class)->where('is_cover', true);
    }

    /**
     * The associated sale record when marked as sold.
     *
     * @return HasOne<Sale, $this>
     */
    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class);
    }

    /**
     * Activity log records related to this listing.
     *
     * @return MorphMany<ActivityLog, $this>
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /**
     * Check if the listing is available for public viewing and purchase.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if the listing has been sold.
     */
    public function isSold(): bool
    {
        return $this->status === 'sold';
    }

    /**
     * Check if the listing is hidden.
     */
    public function isHidden(): bool
    {
        return $this->status === 'hidden';
    }

    /**
     * Generate the WhatsApp direct-chat URL pre-filled with listing context.
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        $moderator = $this->moderator;

        if (! $moderator || ! $moderator->is_active || empty($moderator->whatsapp_number)) {
            return null;
        }

        $cleanNumber = preg_replace('/[^0-9]/', '', $moderator->whatsapp_number);
        $message = "Hello! I am interested in buying Free Fire ID: {$this->title} (UID: {$this->uid}). Is it still available?";

        return 'https://wa.me/'.$cleanNumber.'?text='.urlencode($message);
    }

    /**
     * Scope query to only available listings.
     *
     * @param  Builder<Listing>  $query
     * @return Builder<Listing>
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope query to only sold listings.
     *
     * @param  Builder<Listing>  $query
     * @return Builder<Listing>
     */
    public function scopeSold(Builder $query): Builder
    {
        return $query->where('status', 'sold');
    }

    /**
     * Scope query to only hidden listings.
     *
     * @param  Builder<Listing>  $query
     * @return Builder<Listing>
     */
    public function scopeHidden(Builder $query): Builder
    {
        return $query->where('status', 'hidden');
    }

    /**
     * Scope query to only featured listings.
     *
     * @param  Builder<Listing>  $query
     * @return Builder<Listing>
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope query to listings assigned to a specific user.
     *
     * @param  Builder<Listing>  $query
     * @return Builder<Listing>
     */
    public function scopeAssignedTo(Builder $query, int|User $user): Builder
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $query->where('assigned_to', $userId);
    }
}
