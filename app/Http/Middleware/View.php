<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class View
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        view()->share('__unread', Notification::query()->where('see', '!=', 1)->count());
        view()->share('__notifications', Notification::query()->with('product')->orderBy('id','desc')->paginate(10));
        return $next($request);
    }
}
