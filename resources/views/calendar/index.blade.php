@extends('layouts.app')

@section('title', 'Takvim')

@section('header-title', 'Takvim')

@section('content')
<div class="space-y-6" x-data="dayPlanModal()">
    <!-- Calendar Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h2 class="text-2xl font-bold text-gray-900">
                    @php
                        $monthNames = [
                            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                        ];
                        echo $monthNames[$currentMonth->month] . ' ' . $currentMonth->year;
                    @endphp
                </h2>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('calendar.index', ['month' => $previousMonth->month, 'year' => $previousMonth->year]) }}" 
                       class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('calendar.index') }}" 
                       class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Bugün
                    </a>
                    <a href="{{ route('calendar.index', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" 
                       class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <a href="{{ route('calendar.create') }}" 
               class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                + Yeni Randevu
            </a>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Week Days Header -->
        <div class="grid grid-cols-7 border-b border-gray-200">
            @php
                $weekDays = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
            @endphp
            @foreach($weekDays as $day)
                <div class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 divide-x divide-y divide-gray-200">
            @php
                $startDate = $currentMonth->copy()->startOfMonth()->startOfWeek();
                $endDate = $currentMonth->copy()->endOfMonth()->endOfWeek();
                $currentDay = $startDate->copy();
                $today = now();
            @endphp
            
            @while($currentDay <= $endDate)
                @php
                    $dayKey = $currentDay->format('Y-m-d');
                    $dayEvents = $events[$dayKey] ?? collect();
                    $isCurrentMonth = $currentDay->month == $currentMonth->month;
                    $isToday = $currentDay->isSameDay($today);
                    $isPast = $currentDay->isPast() && !$currentDay->isToday();
                @endphp
                
                <div class="min-h-[150px] max-h-[300px] p-2 {{ $isPast ? 'bg-gray-100 opacity-75' : ($isCurrentMonth ? 'bg-white' : 'bg-gray-50') }} {{ $isToday ? 'bg-primary-50 border-2 border-primary-500' : '' }} flex flex-col">
                    <div class="flex items-center justify-between mb-1 flex-shrink-0">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium {{ $isPast ? 'text-gray-400' : ($isCurrentMonth ? 'text-gray-900' : 'text-gray-400') }} {{ $isToday ? 'text-primary-700' : '' }}">
                                {{ $currentDay->day }}
                            </span>
                            <span class="text-xs {{ $isPast ? 'text-gray-400' : ($isCurrentMonth ? 'text-gray-500' : 'text-gray-300') }}">
                                @php
                                    $dayNames = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
                                    $dayIndex = $currentDay->dayOfWeek; // 0=Pazar, 1=Pazartesi, ..., 6=Cumartesi
                                    // Convert to Monday=0, Tuesday=1, ..., Sunday=6
                                    $mondayBasedIndex = ($dayIndex == 0) ? 6 : ($dayIndex - 1);
                                    echo $dayNames[$mondayBasedIndex];
                                @endphp
                            </span>
                        </div>
                        @if($isToday)
                            <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                        @endif
                    </div>
                    
                    <div class="space-y-1 overflow-y-auto flex-1 min-h-0">
                        @forelse($dayEvents as $event)
                            <a href="{{ route('calendar.edit', $event) }}" 
                               class="block px-2 py-1 text-xs {{ $isPast ? 'bg-gray-200 text-gray-600 hover:bg-gray-300' : 'bg-primary-100 text-primary-800 hover:bg-primary-200' }} rounded transition-colors truncate"
                               title="{{ $event->title }} - {{ $event->start_at->format('H:i') }}">
                                <span class="font-medium">{{ $event->start_at->format('H:i') }}</span>
                                <span class="ml-1">{{ \Illuminate\Support\Str::limit($event->title, 15) }}</span>
                            </a>
                        @empty
                            @if(!$isPast && $isCurrentMonth && ($currentDay->isToday() || $currentDay->isFuture()))
                                <a href="{{ route('calendar.create', ['date' => $currentDay->format('Y-m-d')]) }}" 
                                   class="block mt-2 text-xs text-gray-400 hover:text-primary-600 transition-colors">
                                    + Ekle
                                </a>
                            @endif
                            @if($isPast)
                                <span class="block mt-2 text-xs text-gray-400 italic">Geçmiş</span>
                            @endif
                        @endforelse
                    </div>
                    
                    @if($dayEvents->isNotEmpty())
                        <div class="mt-1 pt-1 border-t border-gray-200 flex-shrink-0">
                            <button type="button" 
                                    @click="openModal('{{ $currentDay->format('Y-m-d') }}')"
                                    class="w-full text-center text-xs text-primary-600 hover:text-primary-800 font-medium transition-colors">
                                Planı Gör ({{ $dayEvents->count() }})
                            </button>
                        </div>
                    @endif
                    
                    @if(!$isPast && $isCurrentMonth && ($currentDay->isToday() || $currentDay->isFuture()))
                        <div class="mt-1 pt-1 border-t border-gray-200 flex-shrink-0">
                            <a href="{{ route('calendar.create', ['date' => $currentDay->format('Y-m-d')]) }}" 
                               class="block text-center text-xs text-gray-400 hover:text-primary-600 transition-colors">
                                + Randevu Ekle
                            </a>
                        </div>
                    @endif
                </div>
                
                @php $currentDay->addDay(); @endphp
            @endwhile
        </div>
    </div>

    <!-- Today's Events Sidebar -->
    @if($todayEvents->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Bugün ({{ $today->format('d.m.Y') }})
            </h3>
            <p class="text-sm text-gray-500 mt-1">{{ $todayEvents->count() }} etkinlik</p>
        </div>
        
        <div class="divide-y divide-gray-200">
            @foreach($todayEvents as $event)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="bg-primary-100 rounded-lg px-3 py-1">
                                <span class="text-sm font-semibold text-primary-700">{{ $event->start_at->format('H:i') }}</span>
                                <span class="text-xs text-primary-600"> - {{ $event->end_at->format('H:i') }}</span>
                            </div>
                            <h4 class="text-base font-semibold text-gray-900">{{ $event->title }}</h4>
                        </div>
                        
                        <div class="space-y-1 text-sm text-gray-600">
                            @if($event->customer)
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>{{ $event->customer->name }}</span>
                            </div>
                            @endif
                            
                            @if($event->listing)
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>{{ \Illuminate\Support\Str::limit($event->listing->title, 40) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="ml-4 flex items-center space-x-2">
                        <a href="{{ route('calendar.edit', $event) }}" 
                           class="px-3 py-1.5 text-sm bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors">
                            Düzenle
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Day Plan Modal -->
    <div x-show="open" 
         x-cloak
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="closeModal()"
         style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
             @click="closeModal()"></div>

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-xl bg-white shadow-2xl sm:my-8 sm:w-full sm:max-w-3xl z-50"
                 @click.stop>
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white" x-text="dateFormatted || 'Yükleniyor...'"></h3>
                            <p class="text-sm text-primary-100 mt-1" x-show="!loading && events.length > 0" x-text="events.length + ' randevu'"></p>
                        </div>
                        <button type="button" @click="closeModal()" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                    <!-- Loading State -->
                    <div x-show="loading" class="text-center py-12">
                        <svg class="animate-spin h-8 w-8 text-primary-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-4 text-gray-600">Yükleniyor...</p>
                    </div>

                    <!-- Events List -->
                    <div x-show="!loading && events.length > 0" class="space-y-4">
                        <template x-for="event in events" :key="event.id">
                            <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4 mb-3">
                                            <!-- Time Badge -->
                                            <div class="flex-shrink-0 bg-primary-100 rounded-lg px-4 py-2">
                                                <div class="text-sm font-bold text-primary-700" x-text="event.start_time"></div>
                                                <div class="text-xs text-primary-600" x-text="event.end_time"></div>
                                            </div>
                                            
                                            <!-- Title -->
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900" x-text="event.title"></h4>
                                            </div>
                                        </div>
                                        
                                        <!-- Event Details -->
                                        <div class="ml-20 space-y-2">
                                            <template x-if="event.customer">
                                                <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span class="font-medium text-gray-700">Müşteri:</span>
                                                    <template x-if="event.customer_id">
                                                        <a :href="'/customers/' + event.customer_id" class="text-primary-600 hover:text-primary-800" x-text="event.customer"></a>
                                                    </template>
                                                    <template x-if="!event.customer_id">
                                                        <span x-text="event.customer"></span>
                                                    </template>
                                                </div>
                                            </template>
                                            
                                            <template x-if="event.listing">
                                                <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                    <span class="font-medium text-gray-700">İlan:</span>
                                                    <template x-if="event.listing_id">
                                                        <a :href="'/listings/' + event.listing_id + '/edit'" class="text-primary-600 hover:text-primary-800" x-text="event.listing"></a>
                                                    </template>
                                                    <template x-if="!event.listing_id">
                                                        <span x-text="event.listing"></span>
                                                    </template>
                                                    <template x-if="event.listing_city">
                                                        <span class="text-gray-400">•</span>
                                                    </template>
                                                    <template x-if="event.listing_city">
                                                        <span class="text-gray-500" x-text="event.listing_city"></span>
                                                    </template>
                                                </div>
                                            </template>
                                            
                                            <template x-if="event.note">
                                                <div class="mt-3 p-3 bg-gray-50 rounded-lg border-l-4 border-primary-500">
                                                    <p class="text-sm text-gray-700" x-text="event.note"></p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="ml-4 flex items-center space-x-2">
                                        <a :href="event.edit_url" 
                                           class="px-3 py-1.5 text-sm bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors">
                                            Düzenle
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Empty State -->
                    <div x-show="!loading && events.length === 0" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-4 text-lg text-gray-900">Bu tarihte randevu bulunmuyor</p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-end space-x-3">
                    <button type="button" @click="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Kapat
                    </button>
                    <template x-if="date">
                        <a :href="'/calendar/create?date=' + date" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                            + Randevu Ekle
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<script>
function dayPlanModal() {
    return {
        open: false,
        loading: false,
        date: '',
        dateFormatted: '',
        events: [],
        openModal(date) {
            this.open = true;
            this.loading = true;
            this.date = date;
            this.events = [];
            
            fetch(`/calendar/day-events?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    this.dateFormatted = data.dateFormatted;
                    this.events = data.events;
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.loading = false;
                });
        },
        closeModal() {
            try {
                this.open = false;
                // Clear data after a short delay to allow transition to complete
                setTimeout(() => {
                    if (!this.open) {
                        this.date = '';
                        this.dateFormatted = '';
                        this.events = [];
                    }
                }, 250);
            } catch (error) {
                console.error('Error closing modal:', error);
            }
        }
    }
}
</script>
@endsection
