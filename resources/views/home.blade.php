@extends('layouts.app')

@section('title', 'ShortURL - URL Shortener')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h1 class="text-4xl font-bold text-center text-gray-800 mb-2">ShortURL</h1>
            <p class="text-gray-500 text-center mb-8">Shorten your long URLs in seconds</p>

            @guest
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
                    <p class="text-indigo-700 text-sm">
                        <a href="{{ route('register') }}" class="font-semibold underline">Create an account</a>
                        to track clicks, view statistics, and customize your short links!
                    </p>
                </div>
            @endguest

            <form action="{{ route('shorten') }}" method="POST">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3">
                    <input
                        type="url"
                        name="url"
                        placeholder="Paste your long URL here..."
                        value="{{ old('url') }}"
                        required
                        class="flex-1 px-5 py-4 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:outline-none text-lg"
                    >
                    <button
                        type="submit"
                        class="px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-semibold text-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        Shorten
                    </button>
                </div>
            </form>

            @if(session('success'))
                <div class="mt-6 p-5 bg-green-50 border-2 border-green-300 rounded-xl">
                    <p class="text-green-700 font-medium mb-3">Your shortened URL:</p>
                    <div class="flex items-center gap-3">
                        <input
                            type="text"
                            id="shortUrl"
                            value="{{ session('short_url') }}"
                            readonly
                            class="flex-1 px-4 py-3 bg-white border-2 border-green-300 rounded-lg text-green-700 font-mono"
                        >
                        <button
                            onclick="copyToClipboard()"
                            class="px-6 py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors"
                        >
                            Copy
                        </button>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mt-6 p-4 bg-red-50 border-2 border-red-300 rounded-xl">
                    @foreach($errors->all() as $error)
                        <p class="text-red-600">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if($recentUrls->count() > 0)
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-gray-500 font-medium mb-4">Recent Public URLs</h3>
                    <ul class="space-y-3">
                        @foreach($recentUrls as $url)
                            <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="overflow-hidden">
                                    <a href="{{ $url->short_url }}" target="_blank" class="text-indigo-600 font-mono font-medium hover:underline">
                                        {{ $url->code }}
                                    </a>
                                    <p class="text-gray-400 text-sm truncate max-w-xs">{{ $url->original_url }}</p>
                                </div>
                                <span class="text-gray-400 text-sm whitespace-nowrap ml-4">{{ $url->clicks }} clicks</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <p class="text-center text-white/80 mt-6">
            @auth
                <a href="{{ route('dashboard') }}" class="underline hover:text-white">Go to Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="underline hover:text-white">Create account</a>
                for analytics and custom links
            @endauth
        </p>
    </div>
</div>

<script>
function copyToClipboard() {
    const input = document.getElementById('shortUrl');
    input.select();
    document.execCommand('copy');

    const btn = event.target;
    const originalText = btn.textContent;
    btn.textContent = 'Copied!';
    setTimeout(() => btn.textContent = originalText, 2000);
}
</script>
@endsection
