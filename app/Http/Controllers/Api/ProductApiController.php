<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Restaurant;
use App\Traits\ApiPaginationTrait;
use App\Traits\ApiResponserTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductApiController extends Controller
{
    use ApiResponserTrait, ApiPaginationTrait;

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        /** @var Restaurant $restaurant */
        $restaurant = $request->restaurant;

        // Ambil parameter limit, default 20, cast ke integer
        $limit = (int)$request->input('limit', 20);

        // Ambil parameter category, default 'all'
        $categoryName = $request->input('category', 'all');

        // Query dasar produk restoran ini
        $query = $restaurant->products()
            ->with(['category', 'options']);

        // Filter berdasarkan kategori jika bukan 'all'
        if ($categoryName !== 'all') {
            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', $categoryName);
            });
        }

        // Eksekusi pagination
        $products = $query->paginate($limit);

        // Transform data collection
        $transformedData = $products->getCollection()->transform(function ($product) {
            return [
                'id' => "product-$product->order_column",
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => (float)$product->price,
                'description' => $product->description,
                'category' => $product->category->name,
                'image' => $product->image ? asset(Storage::url($product->image)) : null,
                'type' => $product->type,
                'options' => $product->options->pluck('name'),
            ];
        });

        // Wrap with pagination metadata using ApiPaginationTrait
        $data = self::autoPaginateWrapperV2($products, $transformedData);

        return $this->successResponse(
            data: $data,
            headers: ['Cache-Control', 'public, max-age=3600']
        );
    }

    public function show(Request $request, Product $product): JsonResponse
    {
        $transformedData = [
            'id' => "product-$product->order_column",
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => (float)$product->price,
            'description' => $product->description,
            'category' => $product->category->name,
            'image' => $product->image ? asset(Storage::url($product->image)) : null,
            'type' => $product->type,
            'options' => $product->options->pluck('name'),
        ];

        return $this->successResponse(
            data: $transformedData,
            headers: ['Cache-Control', 'public, max-age=3600']
        );
    }
}
