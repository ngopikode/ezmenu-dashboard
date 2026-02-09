<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;

class ClientApiController extends Controller
{

    /**
     * handle incoming request
     *
     * @param string $subdomain
     * @return JsonResponse
     */
    public function __invoke(string $subdomain): JsonResponse
    {
        $restaurant = Restaurant::where('subdomain', $subdomain)
            ->where('is_active', true)
            ->first();

        if (!$restaurant) return $this->failResponse(code: 404, message: 'Restaurant not found or not active.');

        // Eager load products with their category and options
        $products = $restaurant->products()->with(['category', 'options'])->get();

        $formattedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float)$product->price,
                'description' => $product->description,
                'category' => $product->category->name, // Get category name
                'image' => $product->image,
                'type' => $product->type,
                'options' => $product->options->pluck('name'), // Get just the names of the options
            ];
        });

        $data = [
            'restaurant' => [
                'name' => $restaurant->name,
                'logo' => $restaurant->logo,
                'theme_color' => $restaurant->theme_color,
                'whatsapp_number' => $restaurant->whatsapp_number,
                'address' => $restaurant->address,
            ],
            'products' => $formattedProducts,
        ];

        return $this->successResponse($data);
    }
}
