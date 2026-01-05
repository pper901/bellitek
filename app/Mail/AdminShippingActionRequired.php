<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminShippingActionRequired extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $order;
    public $errorMessage;

    public function __construct(Order $order, $errorMessage)
    {
        $this->order = $order;
        $this->errorMessage = $errorMessage;
    }

    public function build()
    {
        return $this->subject('URGENT: Shipping Label Failed - Order #' . $this->order->id)
                    ->view('emails.admin.shipping_failed');
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
