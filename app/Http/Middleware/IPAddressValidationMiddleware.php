<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ValideIPAddress;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IPAddressValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $valid_ips = ValideIPAddress::where('status', 1)->pluck('ip_address')->toArray();
        $user_ip = (isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);
        if (!empty($valid_ips) && !empty($user_ip)) {     
            if (in_array($user_ip, $valid_ips)) {
                return $next($request);
            } else {
                Auth::logout();
                return response()->view('errors.ip_address_error');
            }
        } else {
            abort(401);
        }
    }
}
