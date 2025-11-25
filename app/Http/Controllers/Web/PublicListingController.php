<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class PublicListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::where('status', 'active')
            ->with(['photos', 'agent.user']);

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        if ($request->filled('min_area')) {
            $query->where('area_m2', '>=', $request->min_area);
        }

        if ($request->filled('max_area')) {
            $query->where('area_m2', '<=', $request->max_area);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'area_high':
                $query->orderBy('area_m2', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }

        $listings = $query->paginate(12)->withQueryString();

        // Get distinct cities and districts for filters
        $cities = Listing::where('status', 'active')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        $districts = Listing::where('status', 'active')
            ->when($request->filled('city'), function ($q) use ($request) {
                return $q->where('city', $request->city);
            })
            ->distinct()
            ->pluck('district')
            ->filter()
            ->sort()
            ->values();

        return view('web.listings.index', compact('listings', 'cities', 'districts'));
    }

    public function show($slug)
    {
        $listing = Listing::where('slug', $slug)
            ->where('status', 'active')
            ->with(['photos', 'agent.user', 'inquiries'])
            ->firstOrFail();

        // Get similar listings (same city, same type, exclude current)
        $similarListings = Listing::where('status', 'active')
            ->where('id', '!=', $listing->id)
            ->where('city', $listing->city)
            ->where('type', $listing->type)
            ->with(['photos'])
            ->limit(4)
            ->get();

        // Track view (you can implement this later with listing_views table)
        
        return view('web.listings.show', compact('listing', 'similarListings'));
    }
}

