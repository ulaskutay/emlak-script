@extends('layouts.app')

@section('title', 'Talep Detayı')

@section('header-title', 'Talep Detayı')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="space-y-6">
        <div>
            <h3 class="text-lg font-semibold mb-4">Talep Bilgileri</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">İsim</p>
                    <p class="font-medium">{{ $inquiry->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Telefon</p>
                    <p class="font-medium">{{ $inquiry->phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">E-posta</p>
                    <p class="font-medium">{{ $inquiry->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Durum</p>
                    <p class="font-medium">{{ $inquiry->status_label }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-600">Mesaj</p>
                    <p class="font-medium">{{ $inquiry->message }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('inquiries.update', $inquiry) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>Yeni</option>
                        <option value="in_progress" {{ $inquiry->status === 'in_progress' ? 'selected' : '' }}>İşlemde</option>
                        <option value="closed" {{ $inquiry->status === 'closed' ? 'selected' : '' }}>Kapalı</option>
                    </select>
                </div>
                @if(auth()->user()->isAdmin())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Atanan Emlakçı</label>
                    <select name="assigned_agent_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Atanmamış</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ $inquiry->assigned_agent_id == $agent->id ? 'selected' : '' }}>
                                {{ $agent->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            <div class="mt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Güncelle</button>
            </div>
        </form>
    </div>
</div>
@endsection

