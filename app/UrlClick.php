<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlClick extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'short_url_id',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'city',
        'device',
        'browser',
        'platform',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function shortUrl()
    {
        return $this->belongsTo(ShortUrl::class);
    }
}
