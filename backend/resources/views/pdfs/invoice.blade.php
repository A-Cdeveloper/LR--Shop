<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        th, td { padding: 6px 4px; border-bottom: 1px solid #ddd; }
        th { text-align: left; }
        .right { text-align: right; }
        .muted { color: #666; margin-top: 24px; }
    </style>
</head>
<body>
    @php
        $shop = \App\Models\Setting::get('shop.name', config('app.name'));
    @endphp

    <h1>{{ $shop }}</h1>
    <p><strong>{{ __('mail.orders.invoice_heading', ['id' => $order->id]) }}</strong></p>
    <p>{{ __('mail.orders.placed_hello', ['name' => $order->customer_name]) }}</p>
    <p>{{ __('mail.orders.invoice_intro') }}</p>

    <table>
        <thead>
            <tr>
                <th>{{ __('mail.orders.products') }}</th>
                <th class="right">{{ __('mail.orders.qty') }}</th>
                <th class="right">{{ __('mail.orders.price') }}</th>
                <th class="right">{{ __('mail.orders.subtotal') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ number_format($item->price, 2) }} {{ $order->currency }}</td>
                    <td class="right">{{ number_format($item->subtotal, 2) }} {{ $order->currency }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <tr>
            <td>{{ __('mail.orders.subtotal') }}</td>
            <td class="right">{{ number_format($order->total - $order->delivery_price, 2) }} {{ $order->currency }}</td>
        </tr>
        @if ((float) $order->tax_amount > 0)
            <tr>
                <td>{{ __('mail.orders.tax_plain') }}</td>
                <td class="right">{{ number_format($order->tax_amount, 2) }} {{ $order->currency }}</td>
            </tr>
        @endif
        <tr>
            <td>{{ __('mail.orders.delivery', ['method' => $order->delivery_method_name]) }}</td>
            <td class="right">{{ number_format($order->delivery_price, 2) }} {{ $order->currency }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('mail.orders.total') }}</strong></td>
            <td class="right"><strong>{{ number_format($order->total, 2) }} {{ $order->currency }}</strong></td>
        </tr>
    </table>

    <p class="muted">
        {{ __('mail.orders.payment', ['method' => $order->payment_method_name]) }}<br>
        {{ __('mail.orders.payment_status', ['status' => $order->payment_status]) }}<br>
        {{ __('mail.orders.shipping', ['address' => $order->shipping_address.', '.$order->city.', '.$order->zip.', '.$order->country]) }}
    </p>
</body>
</html>
