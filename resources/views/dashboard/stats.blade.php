@extends('layouts.app')

@section('title', 'Statistics - {{ $url->title ?? $url->code }} - ShortURL')

@section('content')
<div class="py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:underline mb-2 inline-block">&larr; Back to Dashboard</a>
                <h1 class="text-3xl font-bold text-gray-800">{{ $url->title ?? 'URL Statistics' }}</h1>
                <p class="text-gray-500 mt-1">
                    <a href="{{ $url->short_url }}" target="_blank" class="text-indigo-600 font-mono hover:underline">{{ $url->short_url }}</a>
                </p>
            </div>
            <div class="text-right">
                <p class="text-4xl font-bold text-indigo-600">{{ number_format($url->clicks) }}</p>
                <p class="text-gray-500">Total Clicks</p>
            </div>
        </div>

        <!-- URL Info Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-500 text-sm mb-1">Original URL</label>
                    <p class="text-gray-800 break-all">{{ $url->original_url }}</p>
                </div>
                <div>
                    <label class="block text-gray-500 text-sm mb-1">Created</label>
                    <p class="text-gray-800">{{ $url->created_at->format('F d, Y H:i') }}</p>
                </div>
            </div>

            <!-- Edit Title -->
            <form action="{{ route('dashboard.urls.update', $url->id) }}" method="POST" class="mt-6 pt-6 border-t border-gray-100">
                @csrf
                @method('PUT')
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-gray-500 text-sm mb-1">Title</label>
                        <input
                            type="text"
                            name="title"
                            value="{{ $url->title }}"
                            placeholder="Add a title for this URL"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none"
                        >
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Period Selector -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-8">
            <div class="flex items-center gap-2">
                <span class="text-gray-600">View by:</span>
                @foreach(['minute' => 'Minute', 'hour' => 'Hour', 'day' => 'Day', 'week' => 'Week', 'month' => 'Month'] as $key => $label)
                    <a
                        href="{{ route('dashboard.urls.show', ['id' => $url->id, 'period' => $key]) }}"
                        class="px-4 py-2 rounded-lg {{ $period === $key ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Clicks Over Time -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Clicks Over Time</h3>
                <canvas id="clicksChart" height="200"></canvas>
            </div>

            <!-- Device Distribution -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Devices</h3>
                <canvas id="deviceChart" height="200"></canvas>
            </div>

            <!-- Browser Distribution -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Browsers</h3>
                <canvas id="browserChart" height="200"></canvas>
            </div>

            <!-- Platform Distribution -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Platforms</h3>
                <canvas id="platformChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Clicks Over Time Chart
    const clicksCtx = document.getElementById('clicksChart').getContext('2d');
    new Chart(clicksCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($clicksData->pluck('period')->reverse()->values()) !!},
            datasets: [{
                label: 'Clicks',
                data: {!! json_encode($clicksData->pluck('count')->reverse()->values()) !!},
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Device Chart
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deviceStats->pluck('device')) !!},
            datasets: [{
                data: {!! json_encode($deviceStats->pluck('count')) !!},
                backgroundColor: ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Browser Chart
    const browserCtx = document.getElementById('browserChart').getContext('2d');
    new Chart(browserCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($browserStats->pluck('browser')) !!},
            datasets: [{
                data: {!! json_encode($browserStats->pluck('count')) !!},
                backgroundColor: ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Platform Chart
    const platformCtx = document.getElementById('platformChart').getContext('2d');
    new Chart(platformCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($platformStats->pluck('platform')) !!},
            datasets: [{
                data: {!! json_encode($platformStats->pluck('count')) !!},
                backgroundColor: ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
@endsection
