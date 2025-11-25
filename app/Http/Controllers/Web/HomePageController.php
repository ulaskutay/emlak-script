<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index(Request $request)
    {
        // Get featured listings - Show all active listings (ignore show_on_web for now to show all panel listings)
        $featuredListings = Listing::where('status', 'active')
            ->with(['photos', 'agent.user'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get latest listings (limit 9)
        $latestListings = Listing::where('status', 'active')
            ->with(['photos', 'agent.user'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();

        // Get newly listed projects (for the new section with filters)
        $newlyListedQuery = Listing::where('status', 'active')
            ->with(['photos', 'agent.user']);
        
        // Apply filters if provided
        if ($request->has('filter_type') && $request->filter_type !== 'all') {
            if (in_array($request->filter_type, ['sale', 'rent'])) {
                $newlyListedQuery->where('type', $request->filter_type);
            } elseif (in_array($request->filter_type, ['apartment', 'office', 'shop', 'villa'])) {
                $newlyListedQuery->where('building_type', $request->filter_type);
            }
        }
        
        $newlyListedProjects = $newlyListedQuery
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get listings grouped by city (top 5 cities)
        $citiesWithListings = Listing::where('status', 'active')
            ->selectRaw('city, COUNT(*) as count')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Get statistics
        $totalListings = Listing::where('status', 'active')->count();
        $saleCount = Listing::where('status', 'active')->where('type', 'sale')->count();
        $rentCount = Listing::where('status', 'active')->where('type', 'rent')->count();

        return view('web.home.index', compact(
            'featuredListings',
            'latestListings',
            'newlyListedProjects',
            'citiesWithListings',
            'totalListings',
            'saleCount',
            'rentCount'
        ));
    }
}

