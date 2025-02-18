<x-email-layout>
    <div>
        <p style="font-weight: bold; color: #274c5b; font-size: 22px; margin: 16px 0 12px;">
            Hello @if (isset($data['name']))
                {{ $data['name'] }}
            @endif,
        </p>
        <p style="color: #545e62;">Thank you for subscribing to Organick’s newsletter! We’re excited to keep you in the
            loop on the latest in organic products, wellness tips, and exclusive offers crafted just for you.</p>
        <p style="color: #274c5b; font-size: 20px; text-decoration: underline;">
            <strong>Here’s what to expect:</strong>
        </p>
        <ul style="color: #274c5b; padding-left: 20px;">
            <li style="margin-bottom: 8px;"><strong>Exclusive Deals:</strong> <span style="color: #545e62;">Enjoy special
                    discounts and early access to new product launches.</span></li>
            <li style="margin-bottom: 8px;"><strong>Wellness Insights: </strong> <span style="color: #545e62;">
                    Get expert tips on living a healthier, more sustainable lifestyle.</span></li>
            <li style="margin-bottom: 8px;"><strong>Seasonal Picks & New Arrivals:</strong><br> <span
                    style="color: #545e62; margin-bottom: 6px;
                        display: inline-block;"> Be the
                    first to know about our fresh finds and curated collections.</span>
            </li>
        </ul>
        <div style="color: #274c5b">
            <p>Verify to accept Emails message For Newsletter</p>
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ $verifyUrl }}"
                    style="background-color: #274c5b; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 10px; display: inline-block; font-size: 18px;">
                    Verify Email
                </a>
            </div>
        </div>
        <div style="color: #274c5b">
            <p>We're thrilled to have you with us on this journey to better health and sustainability. Keep an eye on
                your inbox for your first newsletter!</p>
        </div>
        <div style="color: #274c5b;">
            <p>With gratitude,
            </p>
            <p>The Organick Team</p>
        </div>
    </div>
</x-email-layout>
{{-- subject Thanks for Subscribing to the Organick Newsletter! 🌱 --}}
