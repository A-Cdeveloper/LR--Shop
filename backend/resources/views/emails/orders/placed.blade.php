<x-mail::message>
# Order #{{ $order->id }} received

Hello {{ $order->customer_name }},

We received your order.

<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 13px; margin: 16px 0; border-collapse: collapse;">
    <thead>
        <tr>
            <th align="left" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">Products</th>
            <th align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">Qty</th>
            <th align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">Price</th>
            <th align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td style="border-bottom: 1px solid #edeff2; padding: 8px 0; font-weight:bold;">{{ $item->product_name }}</td>
                <td align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">{{ $item->quantity }}</td>
                <td align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">{{ number_format($item->price, 2) }} {{ $order->currency }}</td>
                <td align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">{{ number_format($item->subtotal, 2) }} {{ $order->currency }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 13px; margin: 24px 0; border-collapse: collapse;">
    <tr>
        <td style="padding: 8px 0;">Subtotal</td>
        <td align="right" style="border-top: 1px solid #edeff2; padding: 8px 0;">{{ number_format($order->total - $order->delivery_price, 2) }} {{ $order->currency }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0;">Delivery ({{ $order->delivery_method_name }})</td>
        <td align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">{{ number_format($order->delivery_price, 2) }} {{ $order->currency }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0;"><strong>Total</strong></td>
        <td align="right" style="padding: 8px 0;"><strong>{{ number_format($order->total, 2) }} {{ $order->currency }}</strong></td>
    </tr>
</table>

<p style="margin: 28px 0;">
Payment: {{ $order->payment_method_name }}<br>
Shipping: {{ $order->shipping_address }}, {{ $order->city }}, {{ $order->zip }}, {{ $order->country }}
</p>

Thanks,<br>
{{ \App\Models\Setting::get('shop.name', config('app.name')) }}
</x-mail::message>
