<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SubdomainHelper
{
    /**
     * Get the current subdomain from the request host.
     *
     * @return string|null
     */
    public static function getCurrentSubdomain(): ?string
    {
        $host = request()->getHost();
        $appUrl = config('app.url');

        // Parse the host from app.url. This correctly handles http/https and extracts only the host.
        $appHost = parse_url($appUrl, PHP_URL_HOST);

        // If parsing fails (unlikely for valid URLs), fall back to the raw config value
        // and assume it might not have a scheme.
        if (!$appHost) {
            $appHost = $appUrl;
        }

        // If the current host is the same as the app's base host, there is no subdomain.
        if ($host === $appHost) {
            return null;
        }


        // If the host ends with the app's base host, extract the subdomain part.
        if (Str::endsWith($host, '.' . $appHost)) {
            return Str::beforeLast($host, '.' . $appHost);
        }

        return null;
    }
}
