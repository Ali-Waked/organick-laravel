<x-email-layout>
    <div>
        <p style="font-weight: bold; color: #274c5b; font-size: 22px; margin: 16px 0 12px;">
            Hello {{ $order->user->full_name }},
        </p>
        <p style="color: #545e62;">Great news! Your order #{{ $order->number }} has been shipped and is on its way to
            you.</p>
        <p style="color: #274c5b; font-size: 20px; text-decoration: underline;">
            <strong>Shipping Details:</strong>
        </p>
        <ul style="color: #274c5b; padding-left: 20px;">
            <li style="margin-bottom: 8px;"><strong>Tracking Number: </strong> <span style="color: #545e62;">
                    {{ $order->created_at->format('Y/m/d') }}</span></li>
            <li style="margin-bottom: 8px;"><strong>Carrier:</strong><br>
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
            <p style="margin-bottom:6px">You can track your order using the link below:
                [Tracking Link]</p>
            <p>Thank you for choosing Organick! We hope you enjoy your purchase.
            </p>
        </div>
        <div style="color: #274c5b;">
            <p>Warm regards,
            </p>
            <p>The Organick Team</p>
        </div>
    </div>
</x-email-layout>
{{-- Subject: Your Order is On Its Way! 🚚 --}}
