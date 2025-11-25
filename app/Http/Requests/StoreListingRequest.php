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
            'agent_id' => 'required_if:user.role,admin|exists:agents,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:sale,rent',
            'status' => 'nullable|in:active,pending,sold,rented,passive',
            'price' => 'required|numeric|min:0',
            'price_period' => 'nullable|in:ay,yil,tam',
            'currency' => 'required|in:TRY,USD,EUR',
            'city' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'address' => 'required|string',
            'area_m2' => 'nullable|integer|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floor' => 'nullable|string|max:255',
            'heating_type' => 'nullable|string|max:255',
            'furnished' => 'nullable|boolean',
            'tags' => 'nullable|string',
            'photos.*' => 'image|max:5120',
            'cover_photo_index' => 'nullable|integer',
        ];
    }
}

