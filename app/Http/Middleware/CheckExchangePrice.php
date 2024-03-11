<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class CheckExchangePrice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->input('exchange_price') == 'USD')
            Config::set('custom.exchange_price', 'USD');
        if ($request->input('exchange_price') == 'EUR')
            Config::set('custom.exchange_price', 'EUR');
        if ($request->input('exchange_price') == 'IRR')
            Config::set('custom.exchange_price', 'IRR');
        if ($request->input('exchange_price') == 'Toman')
            Config::set('custom.exchange_price', 'Toman');

        return $next($request);
    }
}
