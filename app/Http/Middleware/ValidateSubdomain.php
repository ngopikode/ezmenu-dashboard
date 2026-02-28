<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use App\Traits\ApiResponserTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ValidateSubdomain
{
    use ApiResponserTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $subdomain = $request->route('subdomain');
        $restaurant = Restaurant::where('subdomain', $subdomain)->where('is_active', true)->first();

        if (!$restaurant) return $this->failResponse(code: ResponseAlias::HTTP_NOT_FOUND, message: 'Restaurant not found or not active.');

        // Use merge to make it available via $request->input('restaurant') and $request->restaurant
        $request->merge(['restaurant' => $restaurant]);

        // Automatically set the default value for the 'subdomain' parameter in all generated URLs
        URL::defaults(['subdomain' => $subdomain]);

        return $next($request);
    }
}
