<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::with('user')
            ->withCount(['listings', 'activeListings', 'inquiries'])
            ->orderBy('active_listings_count', 'desc')
            ->paginate(15);

        return view('agents.index', compact('agents'));
    }

    public function show(Agent $agent)
    {
        $agent->load(['user', 'listings.photos', 'inquiries.listing']);

        $listings = $agent->listings()
            ->with('photos')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'listings_page');

        $inquiries = $agent->inquiries()
            ->with('listing')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'inquiries_page');

        // Get upcoming calendar events (future or today)
        $calendarEvents = $agent->calendarEvents()
            ->with(['listing', 'customer'])
            ->whereDate('start_at', '>=', now()->toDateString())
            ->orderBy('start_at', 'asc')
            ->limit(10)
            ->get();

        return view('agents.show', compact('agent', 'listings', 'inquiries', 'calendarEvents'));
    }
}

