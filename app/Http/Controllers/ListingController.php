<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ListingController extends Controller
{
    /**
     * Display the public catalog of Free Fire IDs with filters and Pinterest masonry.
     */
    public function index(Request $request): Response
    {
        $query = Listing::query()
            ->available()
            ->with(['moderator:id,name,username,phone,whatsapp_number,is_active', 'images', 'coverImage']);

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('uid', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $category = (string) $request->input('category');
            if ($category === 'evo') {
                $query->where(function ($q) {
                    $q->where('gun_skin_count', '>=', 10)
                        ->orWhere('title', 'like', '%Evo%')
                        ->orWhere('description', 'like', '%Evo%');
                });
            } elseif ($category === 'old') {
                $query->where(function ($q) {
                    $q->where('account_age', 'like', '%Season%')
                        ->orWhere('title', 'like', '%Old%')
                        ->orWhere('account_age', 'like', '%Year%');
                });
            } elseif ($category === 'high_level') {
                $query->where('level', '>=', 70);
            } elseif ($category === 'budget') {
                $query->where('price', '<=', 5000);
            }
        }

        if ($request->filled('price')) {
            $priceRange = (string) $request->input('price');
            if ($priceRange === 'under_2k') {
                $query->where('price', '<=', 2000);
            } elseif ($priceRange === '2k_5k') {
                $query->whereBetween('price', [2000, 5000]);
            } elseif ($priceRange === 'above_5k') {
                $query->where('price', '>', 5000);
            }
        }

        $sort = (string) $request->input('sort', 'newest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'level_desc') {
            $query->orderBy('level', 'desc');
        } else {
            $query->latest();
        }

        $listings = $query->get()->map(function ($listing) {
            $listing->whatsapp_cta_url = $listing->whatsapp_url;

            return $listing;
        });

        $stats = [
            'totalAvailable' => Listing::query()->available()->count(),
            'filteredCount' => $listings->count(),
        ];

        return Inertia::render('Listings/Index', [
            'listings' => $listings,
            'stats' => $stats,
            'filters' => $request->only(['search', 'category', 'price', 'sort']),
        ]);
    }

    /**
     * Display a specific listing detail page.
     */
    public function show(string $slug): Response
    {
        $listing = Listing::query()
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->with(['moderator:id,name,username,phone,whatsapp_number,is_active', 'images', 'coverImage'])
            ->firstOrFail();

        $listing->whatsapp_cta_url = $listing->whatsapp_url;

        $relatedListings = Listing::query()
            ->available()
            ->where('id', '!=', $listing->id)
            ->with(['moderator:id,name,username,phone,whatsapp_number,is_active', 'images', 'coverImage'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($rel) {
                $rel->whatsapp_cta_url = $rel->whatsapp_url;

                return $rel;
            });

        return Inertia::render('Listings/Show', [
            'listing' => $listing,
            'relatedListings' => $relatedListings,
        ]);
    }
}
