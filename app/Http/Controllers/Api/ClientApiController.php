<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Traits\ApiResponserTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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
                'price' => (float)$product->price,
                'description' => $product->description,
                'category' => $product->category->name,
                'image' => $product->image ? asset(Storage::url($product->image)) : null,
                'type' => $product->type,
                'options' => $product->options->pluck('name'),
            ];
        });

        $data = [
            'restaurant' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'logo' => $restaurant->logo ? asset(Storage::url($restaurant->logo)) : null,
                'theme_color' => $restaurant->theme_color,
                'whatsapp_number' => $restaurant->whatsapp_number,
                'address' => $restaurant->address,
                'hero' => [
                    'promo_text' => $restaurant->hero_promo_text,
                    'status_text' => $restaurant->hero_status_text,
                    'headline' => $restaurant->hero_headline,
                    'tagline' => $restaurant->hero_tagline,
                    'instagram_url' => $restaurant->hero_instagram_url,
                ],
                'navbar' => [
                    'brand_text' => $restaurant->navbar_brand_text,
                    'title' => $restaurant->navbar_title,
                    'subtitle' => $restaurant->navbar_subtitle,
                ],
                'seo' => [
                    'title' => $restaurant->seo_title,
                    'description' => $restaurant->seo_description,
                    'keywords' => $restaurant->seo_keywords,
                    'og_title' => $restaurant->og_title,
                    'og_description' => $restaurant->og_description,
                    'og_image' => $restaurant->og_image ? asset(Storage::url($restaurant->og_image)) : null,
                ]
            ],
            'products' => $formattedProducts,
        ];

        return $this->successResponse($data);
    }
}
