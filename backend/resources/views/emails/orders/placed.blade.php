<x-mail::message>
# {{ __('mail.orders.placed_heading', ['id' => $order->id]) }}

{{ __('mail.orders.placed_hello', ['name' => $order->customer_name]) }}

{{ __('mail.orders.placed_intro') }}

<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 13px; margin: 16px 0; border-collapse: collapse;">
    <thead>
        <tr>
            <th align="left" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">{{ __('mail.orders.products') }}</th>
            <th align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">{{ __('mail.orders.qty') }}</th>
            <th align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">{{ __('mail.orders.price') }}</th>
            <th align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">{{ __('mail.orders.subtotal') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td style="border-bottom: 1px solid #edeff2; padding: 8px 0; font-weight:bold;">{{ $item->product_name }}</td>
                <td align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">{{ $item->quantity }}</td>
                <td align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">
                    @if ($item->original_price)
                        <s>{{ number_format($item->original_price, 2) }} {{ $order->currency }}</s>
                        {{ number_format($item->price, 2) }} {{ $order->currency }}
                    @else
                        {{ number_format($item->price, 2) }} {{ $order->currency }}
                    @endif
                </td>
                <td align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">{{ number_format($item->subtotal, 2) }} {{ $order->currency }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 13px; margin: 24px 0; border-collapse: collapse;">
    <tr>
        <td style="padding: 8px 0;">{{ __('mail.orders.subtotal') }}</td>
        <td align="right" style="border-top: 1px solid #edeff2; padding: 8px 0;">{{ number_format($order->total - $order->delivery_price, 2) }} {{ $order->currency }}</td>
    </tr>
    @if ((float) $order->tax_amount > 0)
    <tr>
        <td style="padding: 8px 0;">
            @php
                $taxRates = $order->items->pluck('tax_rate')->unique()->filter(fn ($rate) => (float) $rate > 0)->values();
            @endphp
            @if ($taxRates->count() === 1)
                {{ __('mail.orders.tax', ['rate' => rtrim(rtrim(number_format((float) $taxRates->first(), 2, '.', ''), '0'), '.')]) }}
            @else
                {{ __('mail.orders.tax_plain') }}
            @endif
        </td>
        <td align="right" style="padding: 8px 0;">{{ number_format($order->tax_amount, 2) }} {{ $order->currency }}</td>
    </tr>
    @endif
    <tr>
        <td style="padding: 8px 0;">{{ __('mail.orders.delivery', ['method' => $order->delivery_method_name]) }}</td>
        <td align="right" style="border-bottom: 1px solid #edeff2; padding: 8px 0;">{{ number_format($order->delivery_price, 2) }} {{ $order->currency }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0;"><strong>{{ __('mail.orders.total') }}</strong></td>
        <td align="right" style="padding: 8px 0;"><strong>{{ number_format($order->total, 2) }} {{ $order->currency }}</strong></td>
    </tr>
</table>

<p style="margin: 28px 0;font-size: 13px;">
{{ __('mail.orders.payment', ['method' => $order->payment_method_name]) }}<br>
{{ __('mail.orders.payment_status', ['status' => $order->payment_status]) }}<br>
{{ __('mail.orders.shipping', ['address' => $order->shipping_address.', '.$order->city.', '.$order->zip.', '.$order->country]) }}
</p>

{{ __('mail.orders.thanks') }}<br>
{{ \App\Models\Setting::get('shop.name', config('app.name')) }}
</x-mail::message>
