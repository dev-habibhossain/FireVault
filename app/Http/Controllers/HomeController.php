<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the landing/home page with hero, hot IDs, scam safety guide, and terms.
     */
    public function __invoke(Request $request): Response
    {
        $heroListing = Listing::query()
            ->available()
            ->featured()
            ->with(['moderator:id,name,username,phone,whatsapp_number,is_active', 'images', 'coverImage'])
            ->latest()
            ->first();

        if (! $heroListing) {
            $heroListing = Listing::query()
                ->available()
                ->with(['moderator:id,name,username,phone,whatsapp_number,is_active', 'images', 'coverImage'])
                ->latest()
                ->first();
        }

        if ($heroListing) {
            $heroListing->whatsapp_cta_url = $heroListing->whatsapp_url;
        }

        $hotListings = Listing::query()
            ->available()
            ->where('is_featured', true)
            ->with(['moderator:id,name,username,phone,whatsapp_number,is_active', 'images', 'coverImage'])
            ->latest()
            ->take(6)
            ->get();

        if ($hotListings->count() < 4) {
            $hotListings = Listing::query()
                ->available()
                ->with(['moderator:id,name,username,phone,whatsapp_number,is_active', 'images', 'coverImage'])
                ->latest()
                ->take(6)
                ->get();
        }

        $hotListings->each(function ($listing) {
            $listing->whatsapp_cta_url = $listing->whatsapp_url;
        });

        $stats = [
            'availableCount' => Listing::query()->available()->count(),
            'soldCount' => Listing::query()->sold()->count(),
            'moderatorCount' => User::query()->where('role', 'moderator')->where('is_active', true)->count(),
        ];

        return Inertia::render('Welcome', [
            'heroListing' => $heroListing,
            'hotListings' => $hotListings,
            'stats' => $stats,
        ]);
    }
}
