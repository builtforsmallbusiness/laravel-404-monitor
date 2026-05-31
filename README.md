# laravel-404-monitor

A lightweight 404 monitoring dashboard for Laravel applications. Track missing pages, identify broken internal links, monitor Googlebot crawl errors, and understand where your 404 traffic comes from — all without touching a log file.

![Laravel](https://img.shields.io/badge/Laravel-10%2B-red) ![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue) ![License](https://img.shields.io/badge/license-MIT-green)

## Features

- **Automatic tracking** — no manual instrumentation, middleware registers itself
- **Source detection** — classifies each 404 as `bot`, `internal`, `external`, or `direct`
- **Googlebot flag** — instantly see which missing pages search engines are hitting (critical for SEO)
- **Hit counts** — see which URLs are repeatedly failing, not just that they failed once
- **Last seen timestamps** — know if a problem is ongoing or historical
- **Built-in dashboard** — clean UI with search, source filtering, and clear actions
- **Zero dependencies** — no frontend framework required, dashboard is self-contained

## Installation

```bash
composer require builtforsmallbusiness/laravel-404-monitor
```

Publish and run the migration:

```bash
php artisan vendor:publish --tag=404monitor-migrations
php artisan migrate
```

Publish the config (optional):

```bash
php artisan vendor:publish --tag=404monitor-config
```

That's it. The middleware registers itself automatically via the service provider.

## Dashboard

Access the dashboard at `/_404-monitor` (requires authentication by default).

To customise the route prefix or middleware, publish the config:

```php
// config/404monitor.php

'route_prefix' => '_404-monitor',   // change the URL
'middleware'   => ['web', 'auth'],  // protect with your own middleware
```

## Configuration

```php
return [
    // Enable/disable the dashboard UI
    'dashboard_enabled' => true,

    // Dashboard URL prefix
    'route_prefix' => '_404-monitor',

    // Middleware applied to dashboard routes
    'middleware' => ['web', 'auth'],

    // URL patterns to skip (supports * wildcards)
    'ignored_urls' => [
        '/favicon.ico',
        '/apple-touch-icon*',
        '/robots.txt',
        '/.env',
        '/wp-*',
    ],

    // Partial user agent strings to ignore
    'ignored_user_agents' => [
        // 'SemrushBot',
        // 'AhrefsBot',
    ],

    // Days to retain records (null = keep forever)
    'retention_days' => 90,

    // Database table name
    'table_name' => 'failed_requests',
];
```

## Source Types

| Source | Meaning |
|--------|---------|
| `bot` | Automated crawler or spider (includes Googlebot, Bingbot, etc.) |
| `internal` | Followed a link from within your own site — a broken internal link |
| `external` | Followed a link from another website — a stale backlink |
| `direct` | No referer — direct URL hit or unknown |

**Internal 404s are the most actionable** — they indicate broken links in your own content that you can fix immediately.

## Cleaning Up Old Records

Add the cleanup command to your scheduler to enforce the retention period:

```php
// routes/console.php or app/Console/Kernel.php

Schedule::command('404monitor:clean')->daily();
```

Or run manually:

```bash
php artisan 404monitor:clean
```

## Customising the Dashboard View

Publish the views to override them:

```bash
php artisan vendor:publish --tag=404monitor-views
```

Views will be copied to `resources/views/vendor/404monitor/`.

## Using the Model Directly

```php
use BuiltForSmallBusiness\Laravel404Monitor\Models\FailedRequest;

// Most-hit URLs
FailedRequest::orderByDesc('hit_count')->take(10)->get();

// Googlebot crawl errors
FailedRequest::googlebot()->get();

// Active in the last 24 hours
FailedRequest::recentlyActive(24)->get();

// Broken internal links
FailedRequest::where('source', 'internal')->get();
```

## Why this exists

We built this for [Built For Small Business](https://builtforsmallbusiness.com) after discovering that standard Laravel error logs give you no visibility into *patterns* of 404 errors. A single broken internal link generating 200 hits looks the same as 200 one-off bot probes — until you see the data in a dashboard.

The Googlebot tracking was the specific trigger: knowing which of your missing pages search engines are trying to crawl is directly actionable for SEO.

## License

MIT — see [LICENSE](LICENSE)

---

Built by [Built For Small Business](https://builtforsmallbusiness.com) · [Report an issue](https://github.com/builtforsmallbusiness/laravel-404-monitor/issues)
