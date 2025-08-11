<x-email-layout>
    <div>
        <p style="font-weight: bold; color: #274c5b; font-size: 22px; margin: 16px 0 12px;">
            Hello {{ $contactMessage->name }},
        </p>

        <p style="color: #545e62;">We’ve reviewed your recent inquiry and our support team has prepared the following
            response:</p>

        <div style="background-color: #f7f7f7; padding: 12px 16px; border-left: 4px solid #274c5b; margin: 16px 0;">
            <p style="color: #274c5b; margin: 0;">{{ $contactMessage->reply_message }}</p>
        </div>

        <p style="color: #545e62;">If you have any further questions or need clarification, feel free to reply to this
            email, and we’ll be happy to assist you.</p>

        <div style="color: #274c5b; margin-top: 20px;">
            <p>Best regards,</p>
            <p>The Organick Support Team</p>
        </div>
    </div>
</x-email-layout>