<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogPageView
{
    /**
     * Log every browser request after the response has been prepared.
     *
     * Skips asset requests, health checks, and bot-like user agents
     * that aren't interesting for analytics.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldLog($request)) {
            PageView::create([
                'url'         => $request->fullUrl(),
                'method'      => $request->method(),
                'ip'          => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'referer'     => $request->header('referer'),
                'user_id'     => $request->user()?->id,
                'status_code' => $response->getStatusCode(),
                'viewed_at'   => now(),
            ]);
        }

        return $response;
    }

    protected function shouldLog(Request $request): bool
    {
        // Only log GET/HEAD — skip form submissions, API calls, etc.
        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return false;
        }

        // Skip Vite/static assets and the health endpoint.
        $path = $request->path();
        if (str_starts_with($path, 'build/') || $path === 'up') {
            return false;
        }

        return true;
    }
}
