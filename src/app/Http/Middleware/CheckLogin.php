<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request The incoming HTTP request
     * @param Closure $next The next middleware in the pipeline
     * @return Response The HTTP response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip login check for create/retrieve user endpoint
        if ($request->is('api/users')) {
            return $next($request);
        }

        if (!$request->has('login')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login is required',
            ], 401);
        }

        $login = $request->login;
        $user = User::where('login', $login)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }

        // Add the user to the request for use in controllers
        $request->merge(['user' => $user]);

        return $next($request);
    }
}
