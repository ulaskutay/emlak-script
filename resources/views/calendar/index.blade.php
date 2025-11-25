@extends('layouts.app')

@section('title', 'Takvim')

@section('header-title', 'Takvim')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="mb-4">
        <form method="GET" action="{{ route('calendar.index') }}" class="flex items-center space-x-4">
            <input type="date" name="date" value="{{ $date }}" class="px-4 py-2 border border-gray-300 rounded-lg">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Filtrele</button>
        </form>
    </div>
    
    <div class="space-y-4">
        <h3 class="text-lg font-semibold">{{ \Carbon\Carbon::parse($date)->format('d.m.Y') }} Tarihindeki Etkinlikler</h3>
        
        @forelse($events as $event)
        <div class="border rounded-lg p-4">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-semibold">{{ $event->title }}</h4>
                    <p class="text-sm text-gray-600">{{ $event->start_at->format('H:i') }} - {{ $event->end_at->format('H:i') }}</p>
                    @if($event->listing)
                        <p class="text-sm text-gray-500">İlan: {{ $event->listing->title }}</p>
                    @endif
                    @if($event->customer)
                        <p class="text-sm text-gray-500">Müşteri: {{ $event->customer->name }}</p>
                    @endif
                    @if($event->note)
                        <p class="text-sm text-gray-500 mt-2">{{ $event->note }}</p>
                    @endif
                </div>
                <div class="text-sm text-gray-500">
                    {{ $event->agent->user->name }}
                </div>
            </div>
        </div>
        @empty
        <p class="text-gray-500">Bu tarihte etkinlik bulunmuyor.</p>
        @endforelse
    </div>
</div>
@endsection

