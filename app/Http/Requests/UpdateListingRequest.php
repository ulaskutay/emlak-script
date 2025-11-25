<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends StoreListingRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['agent_id'] = 'nullable|exists:agents,id';
        
        return $rules;
    }
}

