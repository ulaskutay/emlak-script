<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Inquiry;
use App\Models\Customer;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Date range (default: last 30 days)
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days);
        $endDate = Carbon::now();

        // Base queries
        $listingsQuery = Listing::query();
        if ($user->isAgent()) {
            $listingsQuery->where('agent_id', $user->agent->id);
        }

        // General Statistics
        $totalListings = $listingsQuery->count();
        $activeListings = (clone $listingsQuery)->where('status', 'active')->count();
        $soldRentedListings = (clone $listingsQuery)->whereIn('status', ['sold', 'rented'])->count();
        
        // Listing views (will be implemented when web integration is ready)
        $totalViews = 0; // Placeholder for listing views
        $uniqueVisitors = 0; // Placeholder for unique visitors
        
        // Inquiries statistics
        $inquiriesQuery = Inquiry::query();
        if ($user->isAgent()) {
            $agent = $user->agent;
            $inquiriesQuery->where(function($q) use ($agent) {
                $q->where('assigned_agent_id', $agent->id)
                  ->orWhereHas('listing', function($q) use ($agent) {
                      $q->where('agent_id', $agent->id);
                  });
            });
        }
        
        $totalInquiries = $inquiriesQuery->count();
        $newInquiries = (clone $inquiriesQuery)->where('status', 'new')->count();
        $closedInquiries = (clone $inquiriesQuery)->where('status', 'closed')->count();
        
        // Chart data - Listing views over time (placeholder data)
        $viewsChartData = $this->getViewsChartData($days);
        
        // Chart data - Inquiries over time
        $inquiriesChartData = $this->getInquiriesChartData($inquiriesQuery, $days);
        
        // Chart data - Listing types distribution
        $listingTypesData = $this->getListingTypesData($listingsQuery);
        
        // Chart data - Status distribution
        $statusDistributionData = $this->getStatusDistributionData($listingsQuery);
        
        // Top viewed listings (placeholder)
        $topViewedListings = Listing::with('photos')
            ->limit(5)
            ->get()
            ->map(function($listing) {
                return [
                    'id' => $listing->id,
                    'title' => $listing->title,
                    'views' => rand(100, 1000), // Placeholder
                    'city' => $listing->city,
                ];
            })
            ->sortByDesc('views')
            ->take(5)
            ->values();
        
        // Visitor statistics (placeholders - will be filled when tracking is implemented)
        $visitorStats = [
            'total_visitors' => 0,
            'unique_visitors' => 0,
            'page_views' => 0,
            'bounce_rate' => 0,
            'avg_session_duration' => '0:00',
        ];
        
        // Visitor regions (placeholder)
        $visitorRegions = [
            ['region' => 'İstanbul', 'visitors' => 0, 'percentage' => 0],
            ['region' => 'Ankara', 'visitors' => 0, 'percentage' => 0],
            ['region' => 'İzmir', 'visitors' => 0, 'percentage' => 0],
            ['region' => 'Bursa', 'visitors' => 0, 'percentage' => 0],
            ['region' => 'Antalya', 'visitors' => 0, 'percentage' => 0],
        ];
        
        // Visitor channels (placeholder)
        $visitorChannels = [
            ['channel' => 'Direkt', 'visitors' => 0, 'percentage' => 0],
            ['channel' => 'Google', 'visitors' => 0, 'percentage' => 0],
            ['channel' => 'Sosyal Medya', 'visitors' => 0, 'percentage' => 0],
            ['channel' => 'Referans', 'visitors' => 0, 'percentage' => 0],
            ['channel' => 'Diğer', 'visitors' => 0, 'percentage' => 0],
        ];
        
        // Current online visitors (placeholder)
        $onlineVisitors = 0;

        return view('statistics.index', compact(
            'totalListings',
            'activeListings',
            'soldRentedListings',
            'totalViews',
            'uniqueVisitors',
            'totalInquiries',
            'newInquiries',
            'closedInquiries',
            'viewsChartData',
            'inquiriesChartData',
            'listingTypesData',
            'statusDistributionData',
            'topViewedListings',
            'visitorStats',
            'visitorRegions',
            'visitorChannels',
            'onlineVisitors',
            'days'
        ));
    }
    
    private function getViewsChartData($days)
    {
        // Placeholder data - will be replaced with real data from listing_views table
        $labels = [];
        $data = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d.m');
            $data[] = rand(50, 500); // Placeholder
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
    
    private function getInquiriesChartData($query, $days)
    {
        $labels = [];
        $newData = [];
        $closedData = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d.m');
            
            $newCount = (clone $query)
                ->where('status', 'new')
                ->whereDate('created_at', $date->toDateString())
                ->count();
            
            $closedCount = (clone $query)
                ->where('status', 'closed')
                ->whereDate('updated_at', $date->toDateString())
                ->count();
            
            $newData[] = $newCount;
            $closedData[] = $closedCount;
        }
        
        return [
            'labels' => $labels,
            'new' => $newData,
            'closed' => $closedData,
        ];
    }
    
    private function getListingTypesData($query)
    {
        $sales = (clone $query)->where('type', 'sale')->count();
        $rents = (clone $query)->where('type', 'rent')->count();
        
        return [
            'labels' => ['Satılık', 'Kiralık'],
            'data' => [$sales, $rents],
        ];
    }
    
    private function getStatusDistributionData($query)
    {
        $active = (clone $query)->where('status', 'active')->count();
        $pending = (clone $query)->where('status', 'pending')->count();
        $sold = (clone $query)->where('status', 'sold')->count();
        $rented = (clone $query)->where('status', 'rented')->count();
        $passive = (clone $query)->where('status', 'passive')->count();
        
        return [
            'labels' => ['Aktif', 'Beklemede', 'Satıldı', 'Kiralandı', 'Pasif'],
            'data' => [$active, $pending, $sold, $rented, $passive],
        ];
    }
}

