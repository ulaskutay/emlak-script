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
            if ($request->agent_id === 'office') {
                $query->whereNull('agent_id');
            } else {
                $query->where('agent_id', $request->agent_id);
            }
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
        // Agent ID: If admin and selected, use it. If agent, use agent's id. Otherwise null (office).
        $agentId = $user->isAdmin() ? ($request->agent_id ?: null) : ($user->agent ? $user->agent->id : null);

        // Handle video upload if file is provided, otherwise use URL
        $videoPath = null;
        $videoUrl = null;
        
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('listings/videos', 'public');
        } elseif ($request->filled('video_url')) {
            $videoUrl = $request->video_url;
        }

        $listing = Listing::create([
            'agent_id' => $agentId,
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $videoUrl,
            'video_path' => $videoPath,
            'type' => $request->type,
            'status' => $request->status ?? 'pending',
            'price' => $request->price,
            'price_period' => $request->price_period,
            'currency' => $request->currency,
            'city' => $request->city,
            'district' => $request->district,
            'address' => $request->address,
            'latitude' => $request->latitude ?: null,
            'longitude' => $request->longitude ?: null,
            'area_m2' => $request->area_m2,
            'bedrooms' => $request->bedrooms,
            'living_rooms' => $request->living_rooms,
            'total_rooms' => $request->total_rooms,
            'balconies' => $request->balconies,
            'bathrooms' => $request->bathrooms,
            'floor' => $request->floor,
            'building_age' => $request->building_age,
            'total_floors' => $request->total_floors,
            'building_type' => $request->building_type,
            'heating_type' => $request->heating_type,
            'furnished' => $request->boolean('furnished'),
            'furnished_type' => $request->furnished_type,
            'balcony' => $request->boolean('balcony'),
            'parking' => $request->boolean('parking'),
            'garden' => $request->boolean('garden'),
            'pool' => $request->boolean('pool'),
            'elevator' => $request->boolean('elevator'),
            'security' => $request->boolean('security'),
            'terrace' => $request->boolean('terrace'),
            'inside_site' => $request->boolean('inside_site'),
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'published_at' => $request->status === 'active' ? now() : null,
            'show_on_web' => $request->boolean('show_on_web'),
        ]);

        // Handle photos
        if ($request->hasFile('photos')) {
            $this->uploadPhotos($listing, $request->file('photos'), $request->cover_photo_index);
        }

        return redirect()->route('listings.index')->with('success', 'İlan başarıyla oluşturuldu.');
    }

    public function show(Listing $listing)
    {
        // This will be used for web site viewing in the future
        // For now, redirect to edit page
        return redirect()->route('listings.edit', $listing);
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
        
        // Handle video upload/update
        $videoPath = $listing->video_path;
        $videoUrl = $request->video_url;
        
        if ($request->hasFile('video_file')) {
            // Delete old video file if exists
            if ($listing->video_path && Storage::disk('public')->exists($listing->video_path)) {
                Storage::disk('public')->delete($listing->video_path);
            }
            $videoPath = $request->file('video_file')->store('listings/videos', 'public');
            $videoUrl = null; // Clear URL if file is uploaded
        } elseif ($request->filled('video_url')) {
            // Delete old video file if URL is provided
            if ($listing->video_path && Storage::disk('public')->exists($listing->video_path)) {
                Storage::disk('public')->delete($listing->video_path);
            }
            $videoPath = null; // Clear path if URL is provided
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $videoUrl,
            'video_path' => $videoPath,
            'type' => $request->type,
            'status' => $request->status,
            'price' => $request->price,
            'price_period' => $request->price_period,
            'currency' => $request->currency,
            'city' => $request->city,
            'district' => $request->district,
            'address' => $request->address,
            'latitude' => $request->latitude ?: null,
            'longitude' => $request->longitude ?: null,
            'area_m2' => $request->area_m2,
            'bedrooms' => $request->bedrooms,
            'living_rooms' => $request->living_rooms,
            'total_rooms' => $request->total_rooms,
            'balconies' => $request->balconies,
            'bathrooms' => $request->bathrooms,
            'floor' => $request->floor,
            'building_age' => $request->building_age,
            'total_floors' => $request->total_floors,
            'building_type' => $request->building_type,
            'heating_type' => $request->heating_type,
            'furnished' => $request->boolean('furnished'),
            'furnished_type' => $request->furnished_type,
            'balcony' => $request->boolean('balcony'),
            'parking' => $request->boolean('parking'),
            'garden' => $request->boolean('garden'),
            'pool' => $request->boolean('pool'),
            'elevator' => $request->boolean('elevator'),
            'security' => $request->boolean('security'),
            'terrace' => $request->boolean('terrace'),
            'inside_site' => $request->boolean('inside_site'),
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'show_on_web' => $request->boolean('show_on_web'),
        ];
        
        // Add new fields if provided
        if ($request->filled('living_rooms')) {
            $data['living_rooms'] = $request->living_rooms;
        }
        if ($request->filled('total_rooms')) {
            $data['total_rooms'] = $request->total_rooms;
        }
        if ($request->filled('building_age')) {
            $data['building_age'] = $request->building_age;
        }
        if ($request->filled('building_type')) {
            $data['building_type'] = $request->building_type;
        }

        // Update agent_id: admin can set to any agent or null (office), agent can only set their own or null
        if ($user->isAdmin()) {
            $data['agent_id'] = $request->agent_id ?: null;
        } elseif ($user->isAgent()) {
            // Agents can set their own id or null (office)
            $data['agent_id'] = $request->filled('agent_id') && $request->agent_id == $user->agent->id ? $user->agent->id : ($request->agent_id ? null : $listing->agent_id);
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

