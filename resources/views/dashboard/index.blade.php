@extends('layouts.app')

@section('title', 'Dashboard - ShortURL')

@section('content')
<div class="py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <span class="text-gray-500">Welcome, {{ auth()->user()->name }}</span>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-100 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Total URLs</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalUrls }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Total Clicks</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalClicks) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Avg. Clicks/URL</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalUrls > 0 ? number_format($totalClicks / $totalUrls, 1) : 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create New URL -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Create New Short URL</h2>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-700">URL shortened successfully!</p>
                    <div class="flex items-center gap-3 mt-2">
                        <input
                            type="text"
                            value="{{ session('short_url') }}"
                            readonly
                            class="flex-1 px-3 py-2 bg-white border border-green-300 rounded-lg text-green-700 font-mono text-sm"
                        >
                        <button onclick="navigator.clipboard.writeText('{{ session('short_url') }}')" class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600">
                            Copy
                        </button>
                    </div>
                </div>
            @endif

            <form action="{{ route('dashboard.urls.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-5">
                        <label class="block text-gray-600 text-sm mb-1">URL *</label>
                        <input
                            type="url"
                            name="url"
                            placeholder="https://example.com/very-long-url"
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none"
                        >
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-gray-600 text-sm mb-1">Title (optional)</label>
                        <input
                            type="text"
                            name="title"
                            placeholder="My Campaign"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none"
                        >
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-600 text-sm mb-1">Custom Code</label>
                        <input
                            type="text"
                            name="custom_code"
                            placeholder="mylink"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none"
                        >
                    </div>
                    <div class="md:col-span-2 flex items-end">
                        <button
                            type="submit"
                            class="w-full py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors"
                        >
                            Shorten
                        </button>
                    </div>
                </div>
                @if($errors->any())
                    <div class="mt-3 text-red-500 text-sm">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </form>
        </div>

        <!-- URLs List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">Your URLs</h2>
            </div>

            @if($urls->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Short URL</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Original URL</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clicks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($urls as $url)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div>
                                            <a href="{{ $url->short_url }}" target="_blank" class="text-indigo-600 font-mono font-medium hover:underline">
                                                {{ $url->code }}
                                            </a>
                                            @if($url->title)
                                                <p class="text-gray-500 text-sm">{{ $url->title }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-gray-600 text-sm truncate max-w-xs" title="{{ $url->original_url }}">
                                            {{ $url->original_url }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-800 font-semibold">{{ number_format($url->clicks) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 text-sm">
                                        {{ $url->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('dashboard.urls.show', $url->id) }}" class="text-indigo-600 hover:text-indigo-800 mr-3">
                                            Stats
                                        </a>
                                        <form action="{{ route('dashboard.urls.destroy', $url->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this URL?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $urls->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    <p class="text-gray-500">No URLs yet. Create your first short URL above!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
