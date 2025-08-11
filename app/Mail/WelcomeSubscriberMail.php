<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class WelcomeSubscriberMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $unsubscribeUrl;
    /**
     * Create a new message instance.
     */
    public function __construct(public Subscriber $subscriber)
    {
        // $this->unsubscribeUrl = route('subscriber.unsubscribe', ['email' => $subscriber->email]);
        $this->unsubscribeUrl = Config::get('app.front-url') . '/unsubscribe?email=' . $subscriber->email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Organick! 🌿 Thank You for Subscribing',
            from: Config::get('mail.from.address'),
            to: $this->subscriber->email,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.news.welcome_message',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
