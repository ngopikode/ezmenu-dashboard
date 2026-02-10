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

        $imageUrl = $product->image ? (config('app.url') . Storage::url($product->image)) : null;

        // Menggunakan frontend_url_base dari config
        $reactAppBaseUrl = config('app.frontend_url_base');

        $protocol = request()->isSecure() ? 'https' : 'http';
        $fullReactUrl = "{$protocol}://{$restaurant->subdomain}.{$reactAppBaseUrl}";

        return view('product_preview', [
            'restaurant' => $restaurant,
            'product' => $product,
            'image_url' => $imageUrl,
            'react_app_url' => $fullReactUrl,
        ]);
    }
}
