<?php

namespace App\Http\Middleware;

use App\Services\ActivityLoggerService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    public function __construct(
        private readonly ActivityLoggerService $logger
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is('api/*')) {
            $this->logger->logApiRequest(
                method: $request->method(),
                url: $request->fullUrl(),
                statusCode: $response->getStatusCode()
            );
        }

        return $response;
    }
}
