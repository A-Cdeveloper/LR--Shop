<x-mail::message>
    # Order #{{ $order->id }} received

    Hello {{ $order->customer_name }},

    We received your order. Total: **{{ number_format($order->total, 2) }}**.

    <x-mail::table>
        | Product | Qty | Subtotal |
        |:--------|----:|---------:|
        @foreach ($order->items as $item)
        | {{ $item->product_name }} | {{ $item->quantity }} | {{ number_format($item->subtotal, 2) }} |
        @endforeach
    </x-mail::table>

    Shipping: {{ $order->shipping_address }}, {{ $order->city }}, {{ $order->zip }}, {{ $order->country }}

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>