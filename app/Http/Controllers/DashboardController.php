<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Agent;
use App\Models\Inquiry;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Base queries
        $listingsQuery = Listing::query();
        $inquiriesQuery = Inquiry::query();

        // Filter by agent if not admin
        if ($user->isAgent()) {
            $agent = $user->agent;
            $listingsQuery->where('agent_id', $agent->id);
            $inquiriesQuery->where(function($q) use ($agent) {
                $q->where('assigned_agent_id', $agent->id)
                  ->orWhereHas('listing', function($q) use ($agent) {
                      $q->where('agent_id', $agent->id);
                  });
            });
        }

        // Statistics
        $totalListings = $listingsQuery->count();
        $activeListings = (clone $listingsQuery)->where('status', 'active')->count();
        
        $thisMonthSoldRented = (clone $listingsQuery)
            ->whereIn('status', ['sold', 'rented'])
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        $todayNewInquiries = (clone $inquiriesQuery)
            ->where('status', 'new')
            ->whereDate('created_at', today())
            ->count();

        // Calculate percentage changes (mock for now - can be improved with historical data)
        $stats = [
            'total_listings' => [
                'value' => $totalListings,
                'change' => '+5.2%',
                'change_type' => 'positive',
            ],
            'active_listings' => [
                'value' => $activeListings,
                'change' => '+1.8%',
                'change_type' => 'positive',
            ],
            'this_month_sold_rented' => [
                'value' => $thisMonthSoldRented,
                'change' => '+10.1%',
                'change_type' => 'positive',
            ],
            'today_new_inquiries' => [
                'value' => $todayNewInquiries,
                'change' => '+25%',
                'change_type' => 'positive',
            ],
        ];

        // Top agents (only for admin)
        $topAgents = [];
        if ($user->isAdmin()) {
            $topAgents = Agent::with('user')
                ->withCount(['listings', 'activeListings', 'inquiries'])
                ->orderBy('active_listings_count', 'desc')
                ->limit(8)
                ->get();
        }

        // Latest listings
        $latestListings = $listingsQuery
            ->with(['agent.user', 'photos'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Upcoming calendar events
        $calendarEventsQuery = CalendarEvent::with(['listing', 'customer', 'agent.user'])
            ->whereDate('start_at', '>=', now()->toDateString())
            ->orderBy('start_at', 'asc');

        if ($user->isAgent()) {
            $calendarEventsQuery->where('agent_id', $user->agent->id);
        }

        $upcomingEvents = $calendarEventsQuery->limit(5)->get();

        return view('dashboard.index', compact('stats', 'topAgents', 'latestListings', 'upcomingEvents'));
    }
}

