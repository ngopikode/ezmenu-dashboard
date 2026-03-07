<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'restaurant_id',
        'customer_name',
        'order_type',
        'order_info',
        'total_price',
        'source',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            // Generate Hash 6 digit yang keren untuk pesanan baru
            // Menggunakan microtime agar probabilitas duplikat hampir nol
            if (empty($order->order_code)) {
                $order->order_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            }
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
