<?php

namespace BuiltForSmallBusiness\Laravel404Monitor\Models;

use Illuminate\Database\Eloquent\Model;

class FailedRequest extends Model
{
    protected $fillable = [
        'url',
        'user_agent',
        'referer',
        'source',
        'hit_count',
        'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'hit_count'    => 'integer',
    ];

    public function getTable(): string
    {
        return config('404monitor.table_name', 'failed_requests');
    }

    public function scopeGooglebot($query)
    {
        return $query->where('user_agent', 'like', '%Googlebot%');
    }

    public function scopeBots($query)
    {
        return $query->where('source', 'bot');
    }

    public function scopeRecentlyActive($query, int $hours = 24)
    {
        return $query->where('last_seen_at', '>=', now()->subHours($hours));
    }

    public function isGooglebot(): bool
    {
        return str_contains(strtolower($this->user_agent ?? ''), 'googlebot');
    }

    public function getSourceBadgeClass(): string
    {
        return match ($this->source) {
            'bot'      => 'danger',
            'internal' => 'info',
            'external' => 'warning',
            default    => 'secondary',
        };
    }
}
