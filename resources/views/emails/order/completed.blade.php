<x-email-layout>
    <div>
        <p style="font-weight: bold; color: #274c5b; font-size: 22px; margin: 16px 0 12px;">
            Hello {{ $data['name'] }},
        </p>
        <p style="color: #545e62;">We’re pleased to inform you that your order <span
                style="font-weight: bold">#{{ $order->number }}</span> has been completed. Thank you for shopping with
            us!</p>

        <div style="color: #274c5b">
            <p style="margin-bottom:6px">If you have any feedback or need assistance, please don’t hesitate to reach out.
                We would love to hear from you!
            </p>
            <p>Thank you for being a valued customer of <i>Organick</i>. We hope to see you again soon!
            <p>
        </div>
        <div style="color: #274c5b;">
            <p>Best,
            </p>
            <p>The Organick Team</p>
        </div>
    </div>
</x-email-layout>

{{-- Subject: Your Order has Been Completed! 🎉 --}}
