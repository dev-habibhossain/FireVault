<?php

use App\Models\Listing;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('home page renders with hero and hot listings', function () {
    $moderator = User::factory()->moderator()->create();

    $featured = Listing::factory()->available()->featured()->create([
        'assigned_to' => $moderator->id,
        'created_by' => $moderator->id,
        'title' => 'Hot Evo Gun Account',
    ]);

    $response = $this->get('/');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->has('hotListings')
        ->has('stats')
        ->where('heroListing.id', $featured->id)
    );
});

test('listings catalog page renders all available listings and filters', function () {
    $moderator = User::factory()->moderator()->create();

    Listing::factory()->count(3)->available()->create([
        'assigned_to' => $moderator->id,
        'created_by' => $moderator->id,
    ]);

    Listing::factory()->sold()->create([
        'assigned_to' => $moderator->id,
        'created_by' => $moderator->id,
    ]);

    $response = $this->get('/listings');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Listings/Index')
        ->has('listings', 3)
        ->where('stats.totalAvailable', 3)
        ->has('filters')
    );
});

test('listings can be filtered by search keyword', function () {
    $moderator = User::factory()->moderator()->create();

    Listing::factory()->available()->create([
        'assigned_to' => $moderator->id,
        'created_by' => $moderator->id,
        'title' => 'Cobra MP40 Special',
        'uid' => '9988776655',
    ]);

    Listing::factory()->available()->create([
        'assigned_to' => $moderator->id,
        'created_by' => $moderator->id,
        'title' => 'Generic Account',
        'uid' => '1122334455',
    ]);

    $response = $this->get('/listings?search=Cobra');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Listings/Index')
        ->has('listings', 1)
        ->where('listings.0.title', 'Cobra MP40 Special')
    );
});

test('accounts route redirects to listings page', function () {
    $response = $this->get('/accounts');

    $response->assertRedirect('/listings');
});

test('single listing page renders with listing details and related listings', function () {
    $moderator = User::factory()->moderator()->create();

    $listing = Listing::factory()->available()->create([
        'assigned_to' => $moderator->id,
        'created_by' => $moderator->id,
        'title' => 'Sakura Hip Hop VIP ID',
        'slug' => 'sakura-hip-hop-vip-id-1234',
    ]);

    Listing::factory()->count(2)->available()->create([
        'assigned_to' => $moderator->id,
        'created_by' => $moderator->id,
    ]);

    $response = $this->get('/listings/sakura-hip-hop-vip-id-1234');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Listings/Show')
        ->where('listing.id', $listing->id)
        ->where('listing.slug', 'sakura-hip-hop-vip-id-1234')
        ->has('relatedListings')
    );
});
