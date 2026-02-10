<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Show a product preview for social media bots.
     *
     * @param string $subdomain
     * @param int $productId
     * @return \Illuminate\Contracts\View\View
     */
    public function showProductPreview(string $subdomain, int $productId): View
    {
        $restaurant = Restaurant::where('subdomain', $subdomain)->where('is_active', true)->firstOrFail();

        $product = Product::where('restaurant_id', $restaurant->id)->findOrFail($productId);

        // Generate an absolute URL for the image, as required by Open Graph
        $imageUrl = $product->image ? asset(Storage::url($product->image)) : null;

        // Get the base URL for the React app from environment variables
        // e.g., REACT_APP_BASE_URL=ezmenu.com
        $reactAppBaseUrl = config('app.frontend_url_base', 'ngopikode.my.id');

        // Construct the new URL format: subdomain.react_app_base_url
        $protocol = request()->isSecure() ? 'https' : 'http';
        $fullReactUrl = "{$protocol}://{$restaurant->subdomain}.{$reactAppBaseUrl}/menu";

        return view('product_preview', [
            'restaurant' => $restaurant,
            'product' => $product,
            'image_url' => $imageUrl,
            'react_app_url' => $fullReactUrl,
        ]);
    }
}
