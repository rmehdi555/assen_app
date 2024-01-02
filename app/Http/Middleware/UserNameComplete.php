<?php

namespace App\Http\Middleware;

use App\Models\UserAddress;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserNameComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Auth::user()->name != null and \Auth::user()->name != '')
            return $next($request);

        return response()->json([
            'status' => 401,
            'errors' => '',
            'message' => __('messages.register_not_complete_name'),
        ], 401);
    }
}
