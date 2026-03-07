<?php

namespace App\Services\Product;

use App\Helpers\TenantUrl;
use App\Models\Product;
use App\Models\Restaurant;
use App\Services\Service;

class ProductService extends Service
{
    /**
     * Retrieve product based on restaurant and product ID format.
     */
    public function getProduct(Restaurant $restaurant, string $productId): Product
    {
        $orderColumn = explode('-', $productId)[1] ?? null;

        return Product::where('restaurant_id', $restaurant->id)
            ->where('order_column', $orderColumn)
            ->firstOrFail();
    }

    /**
     * Get the full URL of the product image.
     */
    public function getProductImageUrl(Product $product): ?string
    {
        return $product->image ? TenantUrl::asset($product->image) : null;
    }
}
