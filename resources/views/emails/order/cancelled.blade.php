<x-email-layout>
    <div>
        <p style="font-weight: bold; color: #274c5b; font-size: 22px; margin: 16px 0 12px;">
            Hello {{ $data['name'] }},
        </p>
        <p style="color: #545e62;">We regret to inform you that your order <span
                style="font-weight: bold">#{{ $order->number }}</span> has been cancelled. If you did not request this
            cancellation, please contact our support team immediately.</p>

        <div style="color: #274c5b">
            <p style="margin-bottom:6px">If you have any questions or need assistance, we’re here to help!
            </p>
            <p>Thank you for considering Organick. We hope to serve you in the future.
            <p>
        </div>
        <div style="color: #274c5b;">
            <p>Kind regards,
            </p>
            <p>The Organick Team</p>
        </div>
    </div>
</x-email-layout>

{{-- Subject: Your Order Has Been Cancelled --}}
