<x-email-layout>
    <div>
        <p style="font-weight: bold; color: #274c5b; font-size: 22px; margin: 16px 0 12px;">
            Hello {{ $order->customer->full_name }},
        </p>
        <p style="color: #545e62;">Thank you for your order with Organick! We’re excited to let you know that your order
            has been successfully created.</p>
        <p style="color: #274c5b; font-size: 20px; text-decoration: underline;">
            <strong>Order Summary:</strong>
        </p>
        <ul style="color: #274c5b; padding-left: 20px;">
            <li style="margin-bottom: 8px;"><strong>Order Number:</strong> <span
                    style="    font-weight: bold; text-decoration: underline; color: #3F51B5; margin-left: 4px;">#{{ $order->number }}</span>
            </li>
            <li style="margin-bottom: 8px;"><strong>Order Date:</strong> <span style="color: #545e62;">
                    {{ $order->created_at->format('Y/m/d') }}</span></li>
            <li style="margin-bottom: 8px;"><strong>Items Ordered:</strong><br>
                <ul style="color: #274c5b; padding-left: 20px;">
                    @foreach ($order->items as $item)
                        <li>
                            <span
                                style="color: #545e62; margin-bottom: 6px;
                        display: inline-block;">{{ $item->product_name }}</span>
                            <ul style="color: #274c5b; padding-left: 20px;">
                                <li>
                                    <span
                                        style="color: #545e62; margin-bottom: 6px;
                        display: inline-block;">Quantity:
                                        {{ $item->quantity }}</span>
                                </li>
                                <li>
                                    <span
                                        style="color: #545e62; margin-bottom: 6px;
                        display: inline-block;">Total
                                        Price:
                                        {{ Currency::format($item->quantity * $item->price, $order->currency) }}</span>
                                </li>
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>
        <p style="color: #274c5b; font-size: 20px; text-decoration: underline;">
            <strong>Shipping Address:</strong>
        </p>
        <ul style="color: #274c5b; padding-left: 20px;">
            <li style="margin-bottom: 8px;"><strong>Phone Number:</strong> <span
                    style="font-weight: bold; text-decoration: underline; color: #3F51B5; margin-left: 4px;">#{{ $order->address->phone_number }}</span>
            </li>
            <li style="margin-bottom: 8px;"><strong>Countery:</strong> <span
                    style="color: #274c5b">{{ Symfony\Component\Intl\Countries::getName($order->address->country) }}</span>
            </li>
            <li style="margin-bottom: 8px;"><strong>Countery:</strong> <span
                    style="color: #274c5b">{{ $order->address->city }}</span></li>
            <li style="margin-bottom: 8px;"><strong>Street:</strong> <span
                    style="color: #274c5b">{{ $order->address->street }}</span></li>
            <li style="margin-bottom: 8px;"><strong>State:</strong> <span
                    style="color: #274c5b">{{ $order->address->state }}</span></li>
            <li style="margin-bottom: 8px;"><strong>Postal Code:</strong> <span
                    style="color: #274c5b">{{ $order->address->postal_code }}</span></li>
        </ul>
        <div style="color: #274c5b">
            <p style="margin-bottom:6px">We will begin processing your order right away and will send you a notification
                once it has shipped. If you have any questions or need to make any changes to your order, please don’t
                hesitate to reach out to our customer support team.</p>
            <p>Thank you for choosing <i>Organick</i>! We appreciate your support and look forward to serving you again
                soon.
            </p>
        </div>
        <div style="color: #274c5b;">
            <p>Best regards,
            </p>
            <p>The Organick Team</p>
        </div>
    </div>
</x-email-layout>
{{-- subject: Your Order Confirmation from Organick! 🌿 --}}
