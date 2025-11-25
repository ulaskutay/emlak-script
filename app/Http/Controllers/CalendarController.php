<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Listing;
use App\Models\Customer;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = CalendarEvent::with(['listing', 'customer', 'agent.user']);

        if ($user->isAgent()) {
            $query->where('agent_id', $user->agent->id);
        }

        $date = $request->filled('date') ? $request->date : now()->format('Y-m-d');
        
        $query->whereDate('start_at', $date);

        $events = $query->orderBy('start_at')->get();

        return view('calendar.index', compact('events', 'date'));
    }
}

