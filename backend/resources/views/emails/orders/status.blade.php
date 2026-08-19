<x-mail::message>
#
Order #{{ $order->id }} — **{{ $order->status }}**
#

Hello {{ $order->customer_name }},

Your order status has changed from {{ $oldStatus }} to {{ $order->status }}.

<x-mail::table>
| Product | Qty | Subtotal |
|:--------|----:|---------:|
@foreach ($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | {{ number_format($item->subtotal, 2) }} |
@endforeach
</x-mail::table>

**Total:** {{ number_format($order->total, 2) }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>