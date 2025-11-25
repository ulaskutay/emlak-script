<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agent_id' => 'nullable|exists:agents,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video_url' => 'nullable|url|max:500',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm|max:102400', // 100MB max
            'type' => 'required|in:sale,rent',
            'status' => 'nullable|in:active,pending,sold,rented,passive',
            'price' => 'required|numeric|min:0',
            'price_period' => 'nullable|in:ay,yil,tam',
            'currency' => 'required|in:TRY,USD,EUR',
            'city' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'area_m2' => 'nullable|integer|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'living_rooms' => 'nullable|integer|min:0',
            'total_rooms' => 'nullable|integer|min:0',
            'balconies' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floor' => 'nullable|string|max:255',
            'building_age' => 'nullable|integer|min:0',
            'total_floors' => 'nullable|integer|min:0',
            'building_type' => 'nullable|string|max:255',
            'heating_type' => 'nullable|string|max:255',
            'furnished' => 'nullable|boolean',
            'furnished_type' => 'nullable|in:furnished,unfurnished,semi_furnished',
            'balcony' => 'nullable|boolean',
            'parking' => 'nullable|boolean',
            'garden' => 'nullable|boolean',
            'pool' => 'nullable|boolean',
            'elevator' => 'nullable|boolean',
            'security' => 'nullable|boolean',
            'terrace' => 'nullable|boolean',
            'inside_site' => 'nullable|boolean',
            'tags' => 'nullable|string',
            'show_on_web' => 'nullable|boolean',
            'photos.*' => 'image|max:5120',
            'cover_photo_index' => 'nullable|integer',
        ];
    }
}

