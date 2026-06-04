<?php

namespace BuiltForSmallBusiness\Laravel404Monitor\Console;

use Illuminate\Console\Command;

class InfoCommand extends Command
{
    protected $signature = '404monitor:info';
    protected $description = 'Display 404 Monitor dashboard URL and setup info';

    public function handle(): void
    {
        $prefix = config('404monitor.route_prefix', '_404-monitor');
        $url = url($prefix);

        $this->info('');
        $this->info('  ✅ 404 Monitor is active');
        $this->info('');
        $this->line("  Dashboard  → {$url}");
        $this->line("  Config     → php artisan vendor:publish --tag=404monitor-config");
        $this->line("  Views      → php artisan vendor:publish --tag=404monitor-views");
        $this->info('');
    }
    
}
