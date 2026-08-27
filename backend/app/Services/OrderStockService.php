<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderStockService
{
    public function transition(Order $order, string $status, string $paymentStatus): void
    {
        $shouldRestore = $this->shouldRestoreStock($order->status, $status);

        DB::transaction(function () use ($order, $status, $paymentStatus, $shouldRestore) {
            if ($shouldRestore) {
                $this->restore($order);
            }

            $order->update([
                'status' => $status,
                'payment_status' => $paymentStatus,
            ]);
        });
    }

    public function restore(Order $order): void
    {

        $order->loadMissing('items');
        foreach ($order->items as $item) {
            $product = Product::query()
                ->whereKey($item->product_id)
                ->lockForUpdate()
                ->first();

            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }
    }

    public function shouldRestoreStock(string $from, string $to): bool
    {
        return in_array($from, Order::STOCK_HELD_STATUSES, true)
            && in_array($to, Order::STOCK_RELEASED_STATUSES, true);
    }
}
