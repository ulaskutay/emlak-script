<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'listing_id' => 'nullable|exists:listings,id',
            'customer_id' => 'nullable|exists:customers,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'required|string',
            'status' => 'nullable|in:new,in_progress,closed',
            'assigned_agent_id' => 'nullable|exists:agents,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'İsim alanı zorunludur.',
            'phone.required' => 'Telefon alanı zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'message.required' => 'Mesaj alanı zorunludur.',
            'listing_id.exists' => 'Seçilen ilan bulunamadı.',
            'customer_id.exists' => 'Seçilen müşteri bulunamadı.',
            'assigned_agent_id.exists' => 'Seçilen emlakçı bulunamadı.',
        ];
    }
}

