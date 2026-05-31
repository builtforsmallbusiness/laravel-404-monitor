<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    | Enable or disable the built-in monitoring dashboard.
    | Set to false if you only want the tracking middleware without the UI.
    */
    'dashboard_enabled' => env('MONITOR_404_DASHBOARD', true),

    /*
    |--------------------------------------------------------------------------
    | Dashboard Route Prefix
    |--------------------------------------------------------------------------
    | The URI prefix for the dashboard. Accessible at /{prefix}
    | Default: /_404-monitor
    */
    'route_prefix' => env('MONITOR_404_PREFIX', '_404-monitor'),

    /*
    |--------------------------------------------------------------------------
    | Dashboard Middleware
    |--------------------------------------------------------------------------
    | Protect the dashboard with middleware. Add 'auth' to require login,
    | or create your own gate middleware for admin-only access.
    */
    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Ignored URLs
    |--------------------------------------------------------------------------
    | URL patterns to skip tracking. Supports wildcards (*).
    | Useful for ignoring noisy bot probes, favicon misses etc.
    */
    'ignored_urls' => [
        '/favicon.ico',
        '/apple-touch-icon*',
        '/robots.txt',
        '/.env',
        '/.git*',
        '/wp-*',
        '/xmlrpc.php',
    ],

    /*
    |--------------------------------------------------------------------------
    | Ignored User Agents
    |--------------------------------------------------------------------------
    | User agent strings to skip entirely. Partial matches work.
    */
    'ignored_user_agents' => [
        // Add spammy bots you want to exclude from tracking
        // 'SemrushBot',
        // 'AhrefsBot',
    ],

    /*
    |--------------------------------------------------------------------------
    | Retention Period (Days)
    |--------------------------------------------------------------------------
    | How long to keep 404 records. Set to null to keep forever.
    | Run the cleanup command on a schedule to enforce this.
    */
    'retention_days' => env('MONITOR_404_RETENTION', 90),

    /*
    |--------------------------------------------------------------------------
    | Auto Migrate
    |--------------------------------------------------------------------------
    | Automatically run the package migration on boot.
    | Set to false to control migrations manually (recommended for production).
    */
    'auto_migrate' => false,

    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    | The database table used to store 404 records.
    */
    'table_name' => 'failed_requests',

];
