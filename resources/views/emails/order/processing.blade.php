<x-email-layout>
    <div>
        <p style="font-weight: bold; color: #274c5b; font-size: 22px; margin: 16px 0 12px;">
            Hello {{ $order->user->full_name }},
        </p>
        <p style="color: #545e62;">We wanted to let you know that your order #{{ $order->number }} is currently being
            processed. Our team is working hard to prepare your items for shipment.</p>
        <p style="color: #274c5b; font-size: 20px; text-decoration: underline;">
            <strong>Order Summary:</strong>
        </p>
        <ul style="color: #274c5b; padding-left: 20px;">
            <li style="margin-bottom: 8px;"><strong>Order Date:</strong> <span style="color: #545e62;">
                    {{ $order->created_at->format('Y/m/d') }}</span></li>
            <li style="margin-bottom: 8px;"><strong>Items Ordered:</strong><br>
                <ul style="color: #274c5b; padding-left: 20px;">
                    @foreach ($order->items as $item)
                        <li>
                            <span
                                style="color: #545e62; margin-bottom: 6px;
                        display: inline-block;"></span>
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>
        <div style="color: #274c5b">
            <p style="margin-bottom:6px">We appreciate your patience during this time! You’ll receive another
                notification once your order has shipped.</p>
            <p>Thank you for choosing Organick!
            </p>
        </div>
        <div style="color: #274c5b;">
            <p>Best regards,
            </p>
            <p>The Organick Team</p>
        </div>
    </div>
</x-email-layout>
{{-- Subject: Your Order is Being Processed! 🌱 --}}
