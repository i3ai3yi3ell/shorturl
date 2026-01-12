<?php

namespace App\Http\Controllers;

use App\ShortUrl;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    public function index()
    {
        $recentUrls = ShortUrl::whereNull('user_id')->latest()->take(5)->get();
        return view('home', compact('recentUrls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:2048',
        ]);

        $shortUrl = ShortUrl::create([
            'code' => ShortUrl::generateCode(),
            'original_url' => $request->url,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('home')->with([
            'success' => true,
            'short_url' => $shortUrl->short_url,
            'code' => $shortUrl->code,
        ]);
    }

    public function redirect(Request $request, $code)
    {
        $shortUrl = ShortUrl::where('code', $code)->firstOrFail();

        if ($shortUrl->isExpired()) {
            abort(410, 'This link has expired.');
        }

        $shortUrl->recordClick($request);

        return redirect($shortUrl->original_url);
    }
}
