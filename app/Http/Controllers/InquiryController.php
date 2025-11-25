<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Customer;
use App\Models\Agent;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Inquiry::with(['listing', 'customer', 'assignedAgent.user']);

        // Filter by agent if not admin
        if ($user->isAgent()) {
            $agent = $user->agent;
            $query->where(function($q) use ($agent) {
                $q->where('assigned_agent_id', $agent->id)
                  ->orWhereHas('listing', function($q) use ($agent) {
                      $q->where('agent_id', $agent->id);
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('agent_id')) {
            $query->where('assigned_agent_id', $request->agent_id);
        }

        $inquiries = $query->orderBy('created_at', 'desc')->paginate(15);
        $agents = $user->isAdmin() ? Agent::with('user')->get() : collect();

        return view('inquiries.index', compact('inquiries', 'agents'));
    }

    public function show(Inquiry $inquiry)
    {
        $this->authorize('view', $inquiry);

        $inquiry->load(['listing', 'customer', 'assignedAgent.user']);
        $agents = auth()->user()->isAdmin() ? Agent::with('user')->get() : collect();

        return view('inquiries.show', compact('inquiry', 'agents'));
    }

    public function update(Request $request, Inquiry $inquiry)
    {
        $this->authorize('update', $inquiry);

        $validated = $request->validate([
            'status' => 'nullable|in:new,in_progress,closed',
            'assigned_agent_id' => 'nullable|exists:agents,id',
        ]);

        $inquiry->update($validated);

        return back()->with('success', 'Talep başarıyla güncellendi.');
    }
}

