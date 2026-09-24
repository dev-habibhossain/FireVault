<?php

use App\Models\ActivityLog;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\Message;
use App\Models\Notice;
use App\Models\Sale;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user model supports admin and moderator roles and relationships', function () {
    $admin = User::factory()->admin()->create();
    $moderator = User::factory()->moderator()->create();

    expect($admin->isAdmin())->toBeTrue()
        ->and($admin->isModerator())->toBeFalse()
        ->and($moderator->isModerator())->toBeTrue()
        ->and($moderator->isAdmin())->toBeFalse();

    $listing = Listing::factory()->create([
        'assigned_to' => $moderator->id,
        'created_by' => $admin->id,
    ]);

    expect($moderator->assignedListings)->toHaveCount(1)
        ->and($moderator->assignedListings->first()->id)->toBe($listing->id)
        ->and($admin->createdListings)->toHaveCount(1);
});

test('listing model casts and relationships function correctly', function () {
    $moderator = User::factory()->moderator()->create([
        'whatsapp_number' => '+8801712345678',
        'is_active' => true,
    ]);

    $listing = Listing::factory()->available()->create([
        'title' => 'VIP Pro Free Fire Account',
        'uid' => '123456789',
        'price' => '1500.50',
        'level' => 70,
        'assigned_to' => $moderator->id,
        'is_featured' => true,
    ]);

    ListingImage::factory()->cover()->create([
        'listing_id' => $listing->id,
        'image_path' => 'screenshots/cover.webp',
    ]);

    ListingImage::factory()->create([
        'listing_id' => $listing->id,
        'image_path' => 'screenshots/screen2.webp',
        'sort_order' => 1,
    ]);

    expect($listing->isAvailable())->toBeTrue()
        ->and($listing->price)->toBe('1500.50')
        ->and($listing->level)->toBe(70)
        ->and($listing->images)->toHaveCount(2)
        ->and($listing->coverImage)->not->toBeNull()
        ->and($listing->whatsapp_url)->toContain('wa.me/8801712345678')
        ->and($listing->whatsapp_url)->toContain(urlencode('VIP Pro Free Fire Account'));

    expect(Listing::available()->count())->toBe(1)
        ->and(Listing::featured()->count())->toBe(1);
});

test('sale model links listing to moderator and maintains financial record', function () {
    $moderator = User::factory()->moderator()->create();
    $listing = Listing::factory()->sold()->create(['assigned_to' => $moderator->id]);

    $sale = Sale::factory()->create([
        'listing_id' => $listing->id,
        'moderator_id' => $moderator->id,
        'listed_price' => '2000.00',
        'actual_price' => '1850.00',
    ]);

    expect($sale->listing->id)->toBe($listing->id)
        ->and($sale->moderator->id)->toBe($moderator->id)
        ->and($sale->actual_price)->toBe('1850.00')
        ->and($sale->listed_price)->toBe('2000.00');

    expect(Sale::forModerator($moderator)->count())->toBe(1);
});

test('notices track audience targeting and read receipts', function () {
    $admin = User::factory()->admin()->create();
    $moderator1 = User::factory()->moderator()->create();
    $moderator2 = User::factory()->moderator()->create();

    $notice = Notice::factory()->important()->create([
        'created_by' => $admin->id,
        'audience_type' => 'selected',
    ]);

    $notice->recipients()->attach($moderator1->id);

    expect($notice->isImportant())->toBeTrue()
        ->and(Notice::visibleTo($moderator1)->count())->toBe(1)
        ->and(Notice::visibleTo($moderator2)->count())->toBe(0)
        ->and($notice->isReadBy($moderator1))->toBeFalse();

    $notice->markAsReadFor($moderator1);

    expect($notice->isReadBy($moderator1))->toBeTrue();
});

test('todos support assignment, completion, and overdue checks', function () {
    $admin = User::factory()->admin()->create();
    $moderator = User::factory()->moderator()->create();

    $todo = Todo::factory()->create([
        'assigned_to' => $moderator->id,
        'created_by' => $admin->id,
        'status' => 'open',
        'due_date' => now()->addDays(3),
    ]);

    expect($todo->isOpen())->toBeTrue()
        ->and($todo->isCompleted())->toBeFalse()
        ->and(Todo::open()->count())->toBe(1);

    $todo->markCompleted($moderator);

    expect($todo->fresh()->isCompleted())->toBeTrue()
        ->and($todo->fresh()->completed_by)->toBe($moderator->id);

    $todo->reopen();
    expect($todo->fresh()->isOpen())->toBeTrue();
});

test('message threads and unread counts operate accurately', function () {
    $admin = User::factory()->admin()->create();
    $moderator = User::factory()->moderator()->create();

    $msg1 = Message::factory()->create([
        'sender_id' => $admin->id,
        'receiver_id' => $moderator->id,
        'body' => 'Welcome to FireVault staff team!',
    ]);

    expect(Message::between($admin, $moderator)->count())->toBe(1)
        ->and(Message::receivedBy($moderator)->unread()->count())->toBe(1)
        ->and($msg1->isRead())->toBeFalse();

    $msg1->markAsRead();

    expect($msg1->fresh()->isRead())->toBeTrue()
        ->and(Message::receivedBy($moderator)->unread()->count())->toBe(0);
});

test('activity log records actions and preserves json properties', function () {
    $admin = User::factory()->admin()->create();
    $listing = Listing::factory()->create();

    $log = ActivityLog::record(
        action: 'listing.price_updated',
        description: 'Listing price discounted from 5000 to 4500',
        subject: $listing,
        properties: ['old_price' => 5000, 'new_price' => 4500],
        actor: $admin
    );

    expect($log->action)->toBe('listing.price_updated')
        ->and($log->actor_id)->toBe($admin->id)
        ->and($log->properties['old_price'])->toBe(5000)
        ->and($log->subject->id)->toBe($listing->id);
});
