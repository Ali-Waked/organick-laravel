<x-email-layout>
    <div>
        <p style="font-weight: bold; color: #274c5b; font-size: 22px; margin: 16px 0 12px;">
            Hello {{ $data['name'] }},
        </p>
        <p style="color: #545e62;">We wanted to let you know that your order #{{ $order->number }} has been refunded. The
            amount will be credited back to your original payment method within [Time Frame].</p>

        <div style="color: #274c5b">
            <p style="margin-bottom:6px">If you have any questions or need further assistance, please don’t hesitate to
                reach out.
            </p>
            <p>Thank you for your understanding, and we hope to serve you again at <i>Organick!</i></p>
        </div>
        <div style="color: #274c5b;">
            <p>Best regards,
            </p>
            <p>The Organick Team</p>
        </div>
    </div>
</x-email-layout>

{{-- Subject: Your Order Has Been Refunded 🌿 --}}
