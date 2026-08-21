<x-mail::message>
# Order #{{ $order->id }} received

Hello {{ $order->customer_name }},

We received your order. Total: **{{ number_format($order->total, 2) }} {{ $order->currency }}**.

<x-mail::table>
| Product | Qty | Subtotal |
|:--------|----:|---------:|
@foreach ($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | {{ number_format($item->subtotal, 2) }} {{ $order->currency }} |
@endforeach
</x-mail::table>

Delivery: {{ $order->delivery_method_name }} — {{ number_format($order->delivery_price, 2) }} {{ $order->currency }}

Shipping: {{ $order->shipping_address }}, {{ $order->city }}, {{ $order->zip }}, {{ $order->country }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
