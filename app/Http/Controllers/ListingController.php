<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Agent;
use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Listing::with(['agent.user', 'photos']);

        // Filter by agent if not admin
        if ($user->isAgent()) {
            $query->where('agent_id', $user->agent->id);
        } elseif ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        // Filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $listings = $query->orderBy('created_at', 'desc')->paginate(15);

        $agents = $user->isAdmin() ? Agent::with('user')->get() : collect();

        return view('listings.index', compact('listings', 'agents'));
    }

    public function create()
    {
        $user = auth()->user();
        
        $agents = $user->isAdmin() 
            ? Agent::with('user')->get() 
            : collect([$user->agent]);

        return view('listings.create', compact('agents'));
    }

    public function store(StoreListingRequest $request)
    {
        $user = auth()->user();
        $agentId = $user->isAdmin() ? $request->agent_id : $user->agent->id;

        $listing = Listing::create([
            'agent_id' => $agentId,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'type' => $request->type,
            'status' => $request->status ?? 'pending',
            'price' => $request->price,
            'price_period' => $request->price_period,
            'currency' => $request->currency,
            'city' => $request->city,
            'district' => $request->district,
            'address' => $request->address,
            'area_m2' => $request->area_m2,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'floor' => $request->floor,
            'heating_type' => $request->heating_type,
            'furnished' => $request->boolean('furnished'),
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'published_at' => $request->status === 'active' ? now() : null,
        ]);

        // Handle photos
        if ($request->hasFile('photos')) {
            $this->uploadPhotos($listing, $request->file('photos'), $request->cover_photo_index);
        }

        return redirect()->route('listings.index')->with('success', 'İlan başarıyla oluşturuldu.');
    }

    public function show(Listing $listing)
    {
        $this->authorize('view', $listing);
        
        $listing->load(['agent.user', 'photos', 'inquiries.customer']);

        return view('listings.show', compact('listing'));
    }

    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);

        $user = auth()->user();
        $agents = $user->isAdmin() 
            ? Agent::with('user')->get() 
            : collect([$user->agent]);

        $listing->load('photos');

        return view('listings.edit', compact('listing', 'agents'));
    }

    public function update(UpdateListingRequest $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $user = auth()->user();
        
        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'type' => $request->type,
            'status' => $request->status,
            'price' => $request->price,
            'price_period' => $request->price_period,
            'currency' => $request->currency,
            'city' => $request->city,
            'district' => $request->district,
            'address' => $request->address,
            'area_m2' => $request->area_m2,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'floor' => $request->floor,
            'heating_type' => $request->heating_type,
            'furnished' => $request->boolean('furnished'),
            'tags' => $request->tags ? explode(',', $request->tags) : null,
        ];

        if ($user->isAdmin() && $request->filled('agent_id')) {
            $data['agent_id'] = $request->agent_id;
        }

        if ($request->status === 'active' && !$listing->published_at) {
            $data['published_at'] = now();
        }

        $listing->update($data);

        // Handle new photos
        if ($request->hasFile('photos')) {
            $this->uploadPhotos($listing, $request->file('photos'), $request->cover_photo_index);
        }

        // Handle cover photo change
        if ($request->filled('cover_photo_id')) {
            $listing->photos()->update(['is_cover' => false]);
            $listing->photos()->where('id', $request->cover_photo_id)->update(['is_cover' => true]);
        }

        return redirect()->route('listings.index')->with('success', 'İlan başarıyla güncellendi.');
    }

    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);

        // Delete photos
        foreach ($listing->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        $listing->delete();

        return redirect()->route('listings.index')->with('success', 'İlan başarıyla silindi.');
    }

    protected function uploadPhotos(Listing $listing, array $photos, ?int $coverIndex = null): void
    {
        foreach ($photos as $index => $photo) {
            $path = $photo->store('listings', 'public');
            
            $listing->photos()->create([
                'path' => $path,
                'is_cover' => $coverIndex === $index,
                'order' => $index,
            ]);
        }
    }
}

