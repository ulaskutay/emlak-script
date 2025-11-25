@extends('layouts.app')

@section('title', 'Müşteri Detayı')

@section('header-title', 'Müşteri Detayı')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Müşteri Bilgileri</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">İsim</p>
                <p class="font-medium">{{ $customer->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Telefon</p>
                <p class="font-medium">{{ $customer->phone }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">E-posta</p>
                <p class="font-medium">{{ $customer->email ?? '-' }}</p>
            </div>
            @if($customer->note)
            <div class="col-span-2">
                <p class="text-sm text-gray-600">Not</p>
                <p class="font-medium">{{ $customer->note }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Talepler</h3>
        <div class="space-y-4">
            @forelse($customer->inquiries as $inquiry)
            <div class="border-b pb-4">
                <p class="font-medium">{{ $inquiry->listing ? $inquiry->listing->title : 'Genel Talep' }}</p>
                <p class="text-sm text-gray-600">{{ $inquiry->message }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $inquiry->created_at->format('d.m.Y H:i') }}</p>
            </div>
            @empty
            <p class="text-gray-500">Talep bulunamadı.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

