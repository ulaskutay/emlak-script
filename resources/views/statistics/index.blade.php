@extends('layouts.app')

@section('title', 'İstatistikler')

@section('header-title', 'İstatistikler')

@section('content')
<div class="space-y-6">
    <!-- Filter Bar -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('statistics.index') }}" class="flex items-center space-x-4">
            <label class="text-sm font-medium text-gray-700">Zaman Aralığı:</label>
            <div class="relative inline-block">
                <select name="days" id="daysSelect" onchange="this.form.submit()" class="appearance-none px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white cursor-pointer">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Son 7 Gün</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Son 30 Gün</option>
                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>Son 90 Gün</option>
                    <option value="365" {{ $days == 365 ? 'selected' : '' }}>Son 1 Yıl</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Toplam İlan Görüntülenme -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Toplam Görüntülenme</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalViews) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Son {{ $days }} gün</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Benzersiz Ziyaretçi -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Benzersiz Ziyaretçi</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($uniqueVisitors) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Son {{ $days }} gün</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Anlık Ziyaretçi -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Anlık Ziyaretçi</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $onlineVisitors }}</p>
                    <p class="text-xs text-green-600 mt-1">
                        <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                        Canlı
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Toplam Talep -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Toplam Talep</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalInquiries) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $newInquiries }} yeni, {{ $closedInquiries }} kapalı</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- İlan Görüntülenme Grafiği -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">İlan Görüntülenme Trendi</h3>
                <span class="text-xs text-gray-500">Son {{ $days }} gün</span>
            </div>
            <canvas id="viewsChart" height="100"></canvas>
        </div>

        <!-- Talep Trendi -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Talep Trendi</h3>
                <span class="text-xs text-gray-500">Son {{ $days }} gün</span>
            </div>
            <canvas id="inquiriesChart" height="100"></canvas>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- İlan Tipi Dağılımı -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">İlan Tipi Dağılımı</h3>
            <canvas id="listingTypesChart"></canvas>
        </div>

        <!-- İlan Durum Dağılımı -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">İlan Durum Dağılımı</h3>
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <!-- Ziyaretçi İstatistikleri -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Ziyaretçi Bölgeleri -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ziyaretçi Bölgeleri</h3>
            <div class="space-y-3">
                @foreach($visitorRegions as $region)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $region['region'] }}</span>
                        <span class="text-sm text-gray-600">{{ number_format($region['visitors']) }} (%{{ $region['percentage'] }})</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $region['percentage'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            <p class="text-xs text-gray-500 mt-4 text-center">Veriler web entegrasyonu sonrası güncellenecektir</p>
        </div>

        <!-- Ziyaretçi Kanalları -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ziyaretçi Kanalları</h3>
            <canvas id="channelsChart"></canvas>
            <p class="text-xs text-gray-500 mt-4 text-center">Veriler web entegrasyonu sonrası güncellenecektir</p>
        </div>
    </div>

    <!-- Top İlanlar ve Genel İstatistikler -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- En Çok Görüntülenen İlanlar -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">En Çok Görüntülenen İlanlar</h3>
            <div class="space-y-3">
                @forelse($topViewedListings as $index => $listing)
                <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-semibold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $listing['title'] }}</p>
                        <p class="text-xs text-gray-500">{{ $listing['city'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">{{ number_format($listing['views']) }}</p>
                        <p class="text-xs text-gray-500">görüntülenme</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">Henüz görüntülenme verisi bulunmuyor.</p>
                @endforelse
            </div>
            <p class="text-xs text-gray-500 mt-4 text-center">Veriler web entegrasyonu sonrası güncellenecektir</p>
        </div>

        <!-- Genel İstatistikler -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Genel İstatistikler</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Toplam İlan</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($totalListings) }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Aktif İlan</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($activeListings) }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Satılan/Kiralandı</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($soldRentedListings) }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Toplam Sayfa Görüntüleme</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($visitorStats['page_views']) }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Ortalama Oturum Süresi</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $visitorStats['avg_session_duration'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-600">Çıkış Oranı</span>
                    <span class="text-sm font-semibold text-gray-900">%{{ $visitorStats['bounce_rate'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    #daysSelect {
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: none !important;
        background-repeat: no-repeat !important;
        background-position: right 0.5rem center !important;
    }
    #daysSelect::-ms-expand {
        display: none !important;
    }
    #daysSelect::-webkit-appearance {
        -webkit-appearance: none !important;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart colors
    const primaryColor = '#214CC4';
    const colors = {
        blue: '#3B82F6',
        green: '#10B981',
        yellow: '#F59E0B',
        purple: '#8B5CF6',
        red: '#EF4444',
        gray: '#6B7280',
    };

    // İlan Görüntülenme Trendi
    const viewsCtx = document.getElementById('viewsChart').getContext('2d');
    new Chart(viewsCtx, {
        type: 'line',
        data: {
            labels: @json($viewsChartData['labels']),
            datasets: [{
                label: 'Görüntülenme',
                data: @json($viewsChartData['data']),
                borderColor: primaryColor,
                backgroundColor: primaryColor + '20',
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Talep Trendi
    const inquiriesCtx = document.getElementById('inquiriesChart').getContext('2d');
    new Chart(inquiriesCtx, {
        type: 'line',
        data: {
            labels: @json($inquiriesChartData['labels']),
            datasets: [
                {
                    label: 'Yeni Talepler',
                    data: @json($inquiriesChartData['new']),
                    borderColor: colors.blue,
                    backgroundColor: colors.blue + '20',
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Kapanan Talepler',
                    data: @json($inquiriesChartData['closed']),
                    borderColor: colors.green,
                    backgroundColor: colors.green + '20',
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // İlan Tipi Dağılımı
    const typesCtx = document.getElementById('listingTypesChart').getContext('2d');
    new Chart(typesCtx, {
        type: 'doughnut',
        data: {
            labels: @json($listingTypesData['labels']),
            datasets: [{
                data: @json($listingTypesData['data']),
                backgroundColor: [colors.blue, colors.green],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // İlan Durum Dağılımı
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: @json($statusDistributionData['labels']),
            datasets: [{
                data: @json($statusDistributionData['data']),
                backgroundColor: [
                    colors.green,
                    colors.yellow,
                    colors.blue,
                    colors.purple,
                    colors.gray,
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Ziyaretçi Kanalları
    const channelsCtx = document.getElementById('channelsChart').getContext('2d');
    const channelData = @json($visitorChannels);
    new Chart(channelsCtx, {
        type: 'bar',
        data: {
            labels: channelData.map(c => c.channel),
            datasets: [{
                label: 'Ziyaretçi',
                data: channelData.map(c => c.visitors),
                backgroundColor: [
                    primaryColor,
                    colors.blue,
                    colors.purple,
                    colors.green,
                    colors.gray,
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
