<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Customer;
use App\Models\Agent;
use App\Models\Listing;
use App\Http\Requests\StoreInquiryRequest;
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

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('agent_id')) {
            $query->where('assigned_agent_id', $request->agent_id);
        }

        $perPage = $request->get('per_page', 15);
        $inquiries = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
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

    public function create()
    {
        $user = auth()->user();
        
        $listings = Listing::select('id', 'title', 'city', 'district')->orderBy('title')->get();
        $customers = Customer::select('id', 'name', 'phone', 'email')->orderBy('name')->get();
        $agents = $user->isAdmin() ? Agent::with('user')->get() : collect();

        return view('inquiries.create', compact('listings', 'customers', 'agents'));
    }

    public function store(StoreInquiryRequest $request)
    {
        $user = auth()->user();
        
        // Create customer if customer_id is not provided but customer info is
        $customerId = $request->customer_id;
        
        if (!$customerId && $request->filled('name') && $request->filled('phone')) {
            // Check if customer already exists
            $existingCustomer = Customer::where('phone', $request->phone)->first();
            
            if ($existingCustomer) {
                $customerId = $existingCustomer->id;
            } else {
                // Create new customer
                $customer = Customer::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                ]);
                $customerId = $customer->id;
            }
        }

        $data = [
            'listing_id' => $request->listing_id,
            'customer_id' => $customerId,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'message' => $request->message,
            'status' => $request->status ?? 'new',
        ];

        // Set assigned agent
        if ($user->isAdmin() && $request->filled('assigned_agent_id')) {
            $data['assigned_agent_id'] = $request->assigned_agent_id;
        } elseif ($user->isAgent()) {
            $data['assigned_agent_id'] = $user->agent->id;
        }

        Inquiry::create($data);

        return redirect()->route('inquiries.index')->with('success', 'Talep başarıyla oluşturuldu.');
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

