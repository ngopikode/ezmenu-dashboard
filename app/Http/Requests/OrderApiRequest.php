<?php

namespace App\Http\Requests;

use App\Models\Restaurant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        /** @var string $subdomain */
        $subdomain = $this->route('subdomain');

        // The request is authorized only if the subdomain belongs to an active restaurant.
        return Restaurant::where('subdomain', $subdomain)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'order_type' => ['required', Rule::in(['dinein', 'takeaway', 'delivery'])],
            'order_info' => ['nullable', 'string'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'source' => ['required', Rule::in(['whatsapp', 'in-app'])],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
