<?php

namespace App\Http\Controllers;

use App\ShortUrl;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $user = auth()->user();
        $urls = $user->shortUrls()->latest()->paginate(10);
        $totalClicks = $user->total_clicks;
        $totalUrls = $user->shortUrls()->count();

        return view('dashboard.index', compact('urls', 'totalClicks', 'totalUrls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:2048',
            'title' => 'nullable|string|max:255',
            'custom_code' => 'nullable|string|alpha_num|min:4|max:20|unique:short_urls,code',
        ]);

        $code = $request->custom_code ?: ShortUrl::generateCode();

        $shortUrl = auth()->user()->shortUrls()->create([
            'code' => $code,
            'original_url' => $request->url,
            'title' => $request->title,
        ]);

        return redirect()->route('dashboard')->with([
            'success' => true,
            'short_url' => $shortUrl->short_url,
            'code' => $shortUrl->code,
        ]);
    }

    public function show($id)
    {
        $url = auth()->user()->shortUrls()->findOrFail($id);
        $period = request('period', 'day');

        $clicksData = $url->getClicksByPeriod($period);

        $deviceStats = $url->urlClicks()
            ->selectRaw('device, COUNT(*) as count')
            ->groupBy('device')
            ->get();

        $browserStats = $url->urlClicks()
            ->selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')
            ->get();

        $platformStats = $url->urlClicks()
            ->selectRaw('platform, COUNT(*) as count')
            ->groupBy('platform')
            ->get();

        return view('dashboard.stats', compact('url', 'clicksData', 'deviceStats', 'browserStats', 'platformStats', 'period'));
    }

    public function update(Request $request, $id)
    {
        $url = auth()->user()->shortUrls()->findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
        ]);

        $url->update([
            'title' => $request->title,
        ]);

        return redirect()->back()->with('success', 'URL updated successfully!');
    }

    public function destroy($id)
    {
        $url = auth()->user()->shortUrls()->findOrFail($id);
        $url->delete();

        return redirect()->route('dashboard')->with('success', 'URL deleted successfully!');
    }
}
