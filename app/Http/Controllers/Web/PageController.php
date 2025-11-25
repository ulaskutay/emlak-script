<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Agent;

class PageController extends Controller
{
    public function about()
    {
        return view('web.pages.about');
    }

    public function contact()
    {
        return view('web.pages.contact');
    }

    public function agents()
    {
        $agents = Agent::with('user')
            ->withCount(['listings', 'activeListings'])
            ->whereHas('user', function($query) {
                $query->where('status', 'active');
            })
            ->orderBy('active_listings_count', 'desc')
            ->paginate(12);

        return view('web.pages.agents', compact('agents'));
    }
}

