<x-email-layout>
    <div>
        <p style="font-weight: bold; color: #274c5b; font-size: 22px; margin: 16px 0 12px;">
            Hello Support Team,
        </p>
        <p style="color: #545e62;">You have received a new message from the Contact Us form on your website.</p>
        <p style="color: #274c5b; font-size: 20px; text-decoration: underline;">
            <strong>Here are the details:</strong>
        </p>
        <ul style="color: #274c5b; padding-left: 20px; list-style-type: none;">
            <li style="margin-bottom: 8px;"><strong>Name:</strong> <span
                    style="color: #545e62;">{{ $data['name'] }}</span></li>
            <li style="margin-bottom: 8px;"><strong>Email:</strong> <span
                    style="color: #545e62;">{{ $data['email'] }}</span></li>
            @if ($data['company'])
                <li style="margin-bottom: 8px;"><strong>Company:</strong> <span
                        style="color: #545e62;">{{ $data['company'] }}</span></li>
            @endif
            <li style="margin-bottom: 8px;"><strong>Message:</strong><br> <span
                    style="color: #545e62; margin-bottom: 6px;
                        display: inline-block;">{{ $data['message'] }}</span>
            </li>
        </ul>
        <br>
        <div style="color: #274c5b;">
            <p>Regards,</p>
            <p>Your Website Team</p>
        </div>
    </div>
</x-email-layout>
