<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Show a product preview for social media bots.
     *
     * @param Request $request
     * @param string $subdomain
     * @param string $productId
     * @return View
     */
    public function showProductPreview(Request $request, string $subdomain, string $productId): View
    {
        /** @var Restaurant $restaurant */
        $restaurant = $request->restaurant;

        $product = Product::where('restaurant_id', $restaurant->id)->findOrFail($productId);

        // Menggunakan frontend_url_base dari config
        $reactAppBaseUrl = config('app.frontend_url_base');

        $protocol = $request->isSecure() ? 'https' : 'http';
        $fullReactUrl = "$protocol://$subdomain.$reactAppBaseUrl";
        $imageUrl = $product->image ? "$fullReactUrl/" . asset(Storage::url($product->image)) : null;

        return view('product_preview', [
            'restaurant' => $restaurant,
            'product' => $product,
            'image_url' => $imageUrl,
            'react_app_url' => $fullReactUrl,
        ]);
    }
}
