@extends('layouts.app')

@section('title', 'Talepler')

@section('header-title', 'Talepler')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Talep Listesi</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İsim</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telefon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İlan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarih</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlem</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($inquiries as $inquiry)
                <tr>
                    <td class="px-6 py-4">{{ $inquiry->name }}</td>
                    <td class="px-6 py-4">{{ $inquiry->phone }}</td>
                    <td class="px-6 py-4">{{ $inquiry->listing ? $inquiry->listing->title : 'Genel Talep' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($inquiry->status === 'new') bg-blue-100 text-blue-800
                            @elseif($inquiry->status === 'in_progress') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ $inquiry->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $inquiry->created_at->format('d.m.Y') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('inquiries.show', $inquiry) }}" class="text-blue-600 hover:text-blue-900">Görüntüle</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Talep bulunamadı.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $inquiries->links() }}
    </div>
</div>
@endsection

