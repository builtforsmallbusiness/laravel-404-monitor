<?php

namespace BuiltForSmallBusiness\Laravel404Monitor\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use BuiltForSmallBusiness\Laravel404Monitor\Models\FailedRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Track404Middleware
{
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $response = $next($request);

        if ($response->getStatusCode() === 404) {
            $this->record($request);
        }

        return $response;
    }

    protected function record(Request $request): void
    {
        $url = $request->path();

        if ($this->shouldIgnore($url, $request->userAgent() ?? '')) {
            return;
        }

        $userAgent  = $request->userAgent() ?? '';
        $referer    = $request->header('referer', '');
        $source     = $this->detectSource($userAgent, $referer, $request);

        try {
            FailedRequest::upsert(
                [
                    'url'          => $url,
                    'user_agent'   => substr($userAgent, 0, 500),
                    'referer'      => substr($referer, 0, 500),
                    'source'       => $source,
                    'hit_count'    => 1,
                    'last_seen_at' => now(),
                ],
                uniqueBy: ['url'],
                update:   ['hit_count' => \DB::raw('hit_count + 1'), 'last_seen_at' => now()]
            );
        } catch (\Throwable) {
            // Never break the request if tracking fails
        }
    }

  protected function shouldIgnore(string $url, string $userAgent): bool
    {
        $ignoredUrls = config('404monitor.ignored_urls', []);

        foreach ($ignoredUrls as $pattern) {
            // Convert wildcard pattern to regex
            $escaped = preg_quote(ltrim($pattern, '/'), '#');
            $escaped = str_replace('\*', '.*', $escaped);
            $regex = '#^' . $escaped . '$#i';

            if (preg_match($regex, ltrim($url, '/'))) {
                return true;
            }
        }

        $ignoredAgents = config('404monitor.ignored_user_agents', []);

        foreach ($ignoredAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function detectSource(string $userAgent, string $referer, Request $request): string
    {
        $lowerAgent = strtolower($userAgent);

        // Known bots
        $bots = ['googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider', 'yandexbot', 'facebot', 'ia_archiver'];
        foreach ($bots as $bot) {
            if (str_contains($lowerAgent, $bot)) {
                return 'bot';
            }
        }

        // Generic crawler signals
        if (str_contains($lowerAgent, 'bot') || str_contains($lowerAgent, 'spider') || str_contains($lowerAgent, 'crawl')) {
            return 'bot';
        }

        // Internal link (referer is same domain)
        if ($referer && str_contains($referer, $request->getHost())) {
            return 'internal';
        }

        // External referer
        if ($referer) {
            return 'external';
        }

        return 'direct';
    }
}
