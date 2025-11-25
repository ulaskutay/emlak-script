@extends('layouts.app')

@section('title', 'Talepler')

@section('header-title', 'Talepler')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('inquiries.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="İsim, telefon, email ara..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Tüm Durumlar</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Yeni</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>İşlemde</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Kapalı</option>
                </select>
            </div>
            @if(auth()->user()->isAdmin())
            <div>
                <select name="agent_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Tüm Emlakçılar</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                            {{ $agent->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="md:col-span-3 flex space-x-2">
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Filtrele
                </button>
                <a href="{{ route('inquiries.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Inquiries Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Talep Listesi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Müşteri</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İletişim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İlan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        @if(auth()->user()->isAdmin())
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Atanan Emlakçı</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlem</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($inquiries as $inquiry)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $inquiry->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col space-y-1">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <a href="tel:{{ $inquiry->phone }}" class="text-sm text-gray-900 hover:text-primary-600">{{ $inquiry->phone }}</a>
                                </div>
                                @if($inquiry->email)
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <a href="mailto:{{ $inquiry->email }}" class="text-sm text-gray-900 hover:text-primary-600">{{ $inquiry->email }}</a>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($inquiry->listing)
                                <a href="{{ route('listings.edit', $inquiry->listing) }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">
                                    {{ \Illuminate\Support\Str::limit($inquiry->listing->title, 40) }}
                                </a>
                                <div class="text-xs text-gray-500 mt-1">{{ $inquiry->listing->city }}</div>
                            @else
                                <span class="text-sm text-gray-500 italic">Genel Talep</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                @if($inquiry->status === 'new') bg-blue-100 text-blue-800
                                @elseif($inquiry->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ $inquiry->status_label }}
                            </span>
                        </td>
                        @if(auth()->user()->isAdmin())
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($inquiry->assignedAgent)
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                        {{ strtoupper(substr($inquiry->assignedAgent->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm text-gray-900">{{ $inquiry->assignedAgent->user->name }}</span>
                                </div>
                            @else
                                <span class="text-sm text-gray-400 italic">Atanmadı</span>
                            @endif
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $inquiry->created_at->format('d.m.Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $inquiry->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('inquiries.show', $inquiry) }}" class="text-primary-600 hover:text-primary-900 font-medium">Görüntüle</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? '7' : '6' }}" class="px-6 py-8 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2">Henüz talep bulunmuyor.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($inquiries->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-center">
                {{ $inquiries->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

