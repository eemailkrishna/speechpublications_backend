<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EbookDownloadLink extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $ebookItems;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $ebookItems = [])
    {
        $this->order = $order;
        $this->ebookItems = $ebookItems;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your eBook Download Links - Speech Publications',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ebook-download-link',
            with: [
                'order' => $this->order,
                'ebookItems' => $this->ebookItems,
            ],
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
