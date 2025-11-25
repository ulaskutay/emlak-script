<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Listing;
use App\Models\Customer;
use App\Models\Agent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get month and year from request or use current
        $month = $request->filled('month') ? (int)$request->month : now()->month;
        $year = $request->filled('year') ? (int)$request->year : now()->year;
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth()->startOfWeek();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfWeek();
        
        $query = CalendarEvent::with(['listing', 'customer', 'agent.user'])
            ->whereBetween('start_at', [$startDate, $endDate]);

        if ($user->isAgent()) {
            $query->where('agent_id', $user->agent->id);
        }

        $events = $query->orderBy('start_at')->get()->groupBy(function($event) {
            return $event->start_at->format('Y-m-d');
        });

        $currentMonth = Carbon::create($year, $month, 1);
        $previousMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        // Get today's events for sidebar
        $today = $request->filled('date') ? Carbon::parse($request->date) : now();
        $todayQuery = CalendarEvent::with(['listing', 'customer', 'agent.user'])
            ->whereDate('start_at', $today->format('Y-m-d'));

        if ($user->isAgent()) {
            $todayQuery->where('agent_id', $user->agent->id);
        }

        $todayEvents = $todayQuery->orderBy('start_at')->get();

        $agents = $user->isAdmin() ? Agent::with('user')->get() : collect();
        $listings = $user->isAdmin() 
            ? Listing::where('status', 'active')->get() 
            : ($user->isAgent() ? $user->agent->listings()->where('status', 'active')->get() : collect());
        $customers = Customer::all();

        return view('calendar.index', compact('events', 'month', 'year', 'currentMonth', 'previousMonth', 'nextMonth', 'today', 'todayEvents', 'agents', 'listings', 'customers'));
    }

    public function dayEvents(Request $request)
    {
        $user = auth()->user();
        
        $date = $request->filled('date') ? Carbon::parse($request->date) : now();
        
        $query = CalendarEvent::with(['listing', 'customer', 'agent.user'])
            ->whereDate('start_at', $date->format('Y-m-d'));

        if ($user->isAgent()) {
            $query->where('agent_id', $user->agent->id);
        }

        $events = $query->orderBy('start_at')->get();

        $monthNames = [
            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
        ];
        
        $dayNames = [
            0 => 'Pazar', 1 => 'Pazartesi', 2 => 'Salı', 3 => 'Çarşamba',
            4 => 'Perşembe', 5 => 'Cuma', 6 => 'Cumartesi'
        ];
        
        $dateFormatted = $date->format('d') . ' ' . $monthNames[$date->month] . ' ' . $date->format('Y') . ', ' . $dayNames[$date->dayOfWeek];
        
        return response()->json([
            'date' => $date->format('Y-m-d'),
            'dateFormatted' => $dateFormatted,
            'events' => $events->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start_time' => $event->start_at->format('H:i'),
                    'end_time' => $event->end_at->format('H:i'),
                    'customer' => $event->customer ? $event->customer->name : null,
                    'customer_id' => $event->customer_id,
                    'listing' => $event->listing ? $event->listing->title : null,
                    'listing_id' => $event->listing_id,
                    'listing_city' => $event->listing ? $event->listing->city : null,
                    'agent' => $event->agent ? $event->agent->user->name : null,
                    'note' => $event->note,
                    'edit_url' => route('calendar.edit', $event),
                ];
            }),
        ]);
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        
        // Check if date parameter is provided and if it's in the past
        if ($request->filled('date')) {
            $requestedDate = Carbon::parse($request->date)->startOfDay();
            if ($requestedDate->isPast() && !$requestedDate->isToday()) {
                return redirect()->route('calendar.create')->with('error', 'Geriye dönük randevu eklenemez.');
            }
        }
        
        $agents = $user->isAdmin() ? Agent::with('user')->get() : collect([$user->agent]);
        $listings = $user->isAdmin() 
            ? Listing::where('status', 'active')->get() 
            : ($user->isAgent() ? $user->agent->listings()->where('status', 'active')->get() : collect());
        $customers = Customer::all();

        return view('calendar.create', compact('agents', 'listings', 'customers'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'agent_id' => $user->isAdmin() ? 'required|exists:agents,id' : 'nullable',
            'listing_id' => 'nullable|exists:listings,id',
            'customer_id' => 'nullable|exists:customers,id',
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'note' => 'nullable|string',
        ]);

        $startAt = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $endAt = Carbon::parse($validated['start_date'] . ' ' . $validated['end_time']);

        // Ensure date is not in the past
        if ($startAt->isPast() && !$startAt->isToday()) {
            return back()->withErrors(['start_date' => 'Geriye dönük randevu eklenemez.'])->withInput();
        }

        // If today, ensure time is not in the past
        if ($startAt->isToday() && $startAt->isPast()) {
            return back()->withErrors(['start_time' => 'Geriye dönük saat seçilemez.'])->withInput();
        }

        // Ensure end time is after start time
        if ($endAt <= $startAt) {
            return back()->withErrors(['end_time' => 'Bitiş saati, başlangıç saatinden sonra olmalıdır.'])->withInput();
        }

        $agentId = $user->isAdmin() ? $validated['agent_id'] : $user->agent->id;

        CalendarEvent::create([
            'title' => $validated['title'],
            'agent_id' => $agentId,
            'listing_id' => $validated['listing_id'] ?? null,
            'customer_id' => $validated['customer_id'] ?? null,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('calendar.index')->with('success', 'Randevu başarıyla oluşturuldu.');
    }

    public function edit(CalendarEvent $calendar)
    {
        $user = auth()->user();

        // Check authorization
        if ($user->isAgent() && $calendar->agent_id !== $user->agent->id) {
            abort(403);
        }

        $agents = $user->isAdmin() ? Agent::with('user')->get() : collect([$user->agent]);
        $listings = $user->isAdmin() 
            ? Listing::where('status', 'active')->get() 
            : ($user->isAgent() ? $user->agent->listings()->where('status', 'active')->get() : collect());
        $customers = Customer::all();

        return view('calendar.edit', compact('calendar', 'agents', 'listings', 'customers'));
    }

    public function update(Request $request, CalendarEvent $calendar)
    {
        $user = auth()->user();

        // Check authorization
        if ($user->isAgent() && $calendar->agent_id !== $user->agent->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'agent_id' => $user->isAdmin() ? 'required|exists:agents,id' : 'nullable',
            'listing_id' => 'nullable|exists:listings,id',
            'customer_id' => 'nullable|exists:customers,id',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'note' => 'nullable|string',
        ]);

        $startAt = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $endAt = Carbon::parse($validated['start_date'] . ' ' . $validated['end_time']);

        // Ensure end time is after start time
        if ($endAt <= $startAt) {
            return back()->withErrors(['end_time' => 'Bitiş saati, başlangıç saatinden sonra olmalıdır.'])->withInput();
        }

        if ($user->isAdmin() && $validated['agent_id']) {
            $calendar->agent_id = $validated['agent_id'];
        }

        $calendar->update([
            'title' => $validated['title'],
            'listing_id' => $validated['listing_id'] ?? null,
            'customer_id' => $validated['customer_id'] ?? null,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('calendar.index')->with('success', 'Randevu başarıyla güncellendi.');
    }

    public function destroy(CalendarEvent $calendar)
    {
        $user = auth()->user();

        // Check authorization
        if ($user->isAgent() && $calendar->agent_id !== $user->agent->id) {
            abort(403);
        }

        $calendar->delete();

        return redirect()->route('calendar.index')->with('success', 'Randevu başarıyla silindi.');
    }
}

