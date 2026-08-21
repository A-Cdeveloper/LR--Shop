<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    public const STOCK_HELD_STATUSES = [
        'pending',
        'processing',
        'completed',
    ];

    public const STOCK_RELEASED_STATUSES = [
        'cancelled',
        'failed',
        'refunded',
    ];

    public const STATUSES = [
        ...self::STOCK_HELD_STATUSES,
        ...self::STOCK_RELEASED_STATUSES,
    ];

    public const PAYMENT_STATUSES = [
        'pending',
        'paid',
        'failed',
        'refunded',
    ];

    protected $fillable = [
        'user_id',
        'status',
        'total',
        'currency',
        'customer_name',
        'customer_phone',
        'shipping_address',
        'city',
        'state',
        'zip',
        'country',
        'delivery_method_id',
        'delivery_method_name',
        'delivery_price',
        'payment_method_id',
        'payment_method_name',
        'payment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {

        if (blank($status)) {
            return $query;
        }

        if (! in_array($status, self::STATUSES, true)) {
            abort(response()->json([
                'message' => __('api.orders.invalid_status'),
            ], 422));
        }

        return $query->where('status', $status);
    }


    public function scopeSortBy(Builder $query, ?string $field, ?string $direction): Builder
    {

        $allowedFields = ['total', 'created_at'];
        $direction = strtolower($direction ?? 'asc') === 'desc' ? 'desc' : 'asc';

        if (! in_array($field, $allowedFields, true)) {
            return $query->orderBy('created_at', 'desc'); // default
        }

        return $query->orderBy($field, $direction);
    }
}
