<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $http_method = $request->method();

        if ($http_method == 'POST' || $http_method == 'PUT' || $http_method == 'DELETE') {
            return response()->json([
                'message' => 'no permision to create, update and delete',
            ], 403);
        }

        return $next($request);
    }
}
