<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        if (!$response instanceof JsonResponse) {
            if ($response instanceof Response) {
                $content = $response->getContent();
                $json = json_decode($content, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return new JsonResponse($json, $response->getStatusCode(), $response->headers->all());
                }

                return new JsonResponse([
                    'data' => $content,
                    'status_code' => $response->getStatusCode()
                ], $response->getStatusCode(), $response->headers->all());
            }
        }

        return $response;
    }
}
