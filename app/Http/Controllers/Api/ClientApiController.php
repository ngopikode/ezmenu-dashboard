<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Traits\ApiResponserTrait;
use Illuminate\Http\JsonResponse;

class ClientApiController extends Controller
{
    use ApiResponserTrait;

    /**
     * Handle the incoming request.
     *
     * @param string $subdomain
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(string $subdomain): JsonResponse
    {
        $restaurant = Restaurant::where('subdomain', $subdomain)
            ->where('is_active', true)
            ->first();

        if (!$restaurant) {
            return $this->failResponse(message: 'Restaurant not found or not active.', code: 404);
        }

        // Eager load products with their category and options
        $products = $restaurant->products()->with(['category', 'options'])->get();

        $formattedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'description' => $product->description,
                'category' => $product->category->name, // Get category name
                'image' => asset($product->image), // Use asset() to generate full URL
                'type' => $product->type,
                'options' => $product->options->pluck('name'), // Get just the names of the options
            ];
        });

        $data = [
            'restaurant' => [
                'id' => $restaurant->id, // ID restoran ditambahkan
                'name' => $restaurant->name,
                'logo' => asset($restaurant->logo),
                'theme_color' => $restaurant->theme_color,
                'whatsapp_number' => $restaurant->whatsapp_number,
                'address' => $restaurant->address,
                // Objek SEO baru
                'seo' => [
                    'title' => $restaurant->seo_title,
                    'description' => $restaurant->seo_description,
                    'keywords' => $restaurant->seo_keywords,
                    'og_title' => $restaurant->og_title,
                    'og_description' => $restaurant->og_description,
                    'og_image' => asset($restaurant->og_image),
                ]
            ],
            'products' => $formattedProducts,
        ];

        return $this->successResponse($data);
    }
}
