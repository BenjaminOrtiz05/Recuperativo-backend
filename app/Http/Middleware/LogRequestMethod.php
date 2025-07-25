<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestMethod
{
    public function handle(Request $request, Closure $next)
    {
        Log::info("Petición recibida: {$request->method()} {$request->fullUrl()}");
        return $next($request);
    }
}
