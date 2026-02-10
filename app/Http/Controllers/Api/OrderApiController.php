<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class OrderApiController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'customer_name' => 'required|string|max:255',
            'order_type' => 'required|in:dinein,takeaway,delivery',
            'order_info' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'source' => 'required|in:whatsapp,in-app',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->failResponse($validator->errors());
        }

        try {
            $order = DB::transaction(function () use ($request) {
                $order = Order::create([
                    'restaurant_id' => $request->restaurant_id,
                    'customer_name' => $request->customer_name,
                    'order_type' => $request->order_type,
                    'order_info' => $request->order_info,
                    'total_price' => $request->total_price,
                    'source' => $request->source,
                    'status' => 'pending', // Default status
                ]);

                foreach ($request->items as $item) {
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'product_name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                return $order;
            });

            return $this->successResponse($order, 'Order created successfully.', 201);

        } catch (Throwable $e) {
            return $this->errorResponse($e, 'Failed to create order.', 500, $request);
        }
    }
}
