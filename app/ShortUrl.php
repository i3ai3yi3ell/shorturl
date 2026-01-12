<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShortUrl extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'original_url',
        'title',
        'clicks',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public static function generateCode($length = 6)
    {
        do {
            $code = Str::random($length);
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function urlClicks()
    {
        return $this->hasMany(UrlClick::class);
    }

    public function getShortUrlAttribute()
    {
        return url($this->code);
    }

    public function recordClick($request)
    {
        $this->increment('clicks');

        $userAgent = $request->userAgent();

        $this->urlClicks()->create([
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'referer' => $request->header('referer'),
            'device' => $this->getDevice($userAgent),
            'browser' => $this->getBrowser($userAgent),
            'platform' => $this->getPlatform($userAgent),
            'clicked_at' => now(),
        ]);
    }

    public function isExpired()
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    public function getClicksByPeriod($period = 'day', $limit = 30)
    {
        $query = $this->urlClicks();

        switch ($period) {
            case 'minute':
                return $query->selectRaw('DATE_FORMAT(clicked_at, "%Y-%m-%d %H:%i") as period, COUNT(*) as count')
                    ->groupBy('period')
                    ->orderBy('period', 'desc')
                    ->limit($limit)
                    ->get();
            case 'hour':
                return $query->selectRaw('DATE_FORMAT(clicked_at, "%Y-%m-%d %H:00") as period, COUNT(*) as count')
                    ->groupBy('period')
                    ->orderBy('period', 'desc')
                    ->limit($limit)
                    ->get();
            case 'day':
                return $query->selectRaw('DATE(clicked_at) as period, COUNT(*) as count')
                    ->groupBy('period')
                    ->orderBy('period', 'desc')
                    ->limit($limit)
                    ->get();
            case 'week':
                return $query->selectRaw('YEARWEEK(clicked_at) as period, COUNT(*) as count')
                    ->groupBy('period')
                    ->orderBy('period', 'desc')
                    ->limit($limit)
                    ->get();
            case 'month':
                return $query->selectRaw('DATE_FORMAT(clicked_at, "%Y-%m") as period, COUNT(*) as count')
                    ->groupBy('period')
                    ->orderBy('period', 'desc')
                    ->limit($limit)
                    ->get();
        }
    }

    private function getDevice($userAgent)
    {
        if (preg_match('/mobile/i', $userAgent)) return 'Mobile';
        if (preg_match('/tablet/i', $userAgent)) return 'Tablet';
        return 'Desktop';
    }

    private function getBrowser($userAgent)
    {
        if (preg_match('/Chrome/i', $userAgent)) return 'Chrome';
        if (preg_match('/Firefox/i', $userAgent)) return 'Firefox';
        if (preg_match('/Safari/i', $userAgent)) return 'Safari';
        if (preg_match('/Edge/i', $userAgent)) return 'Edge';
        if (preg_match('/Opera/i', $userAgent)) return 'Opera';
        return 'Other';
    }

    private function getPlatform($userAgent)
    {
        if (preg_match('/Windows/i', $userAgent)) return 'Windows';
        if (preg_match('/Mac/i', $userAgent)) return 'Mac';
        if (preg_match('/Linux/i', $userAgent)) return 'Linux';
        if (preg_match('/Android/i', $userAgent)) return 'Android';
        if (preg_match('/iOS|iPhone|iPad/i', $userAgent)) return 'iOS';
        return 'Other';
    }
}
