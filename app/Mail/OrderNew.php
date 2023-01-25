<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

use App\Models\Orders;

class OrderNew extends Mailable
{
    use Queueable, SerializesModels;
    
    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Orders $order)
    {
        $this->order = $order;
    }

    /**
     * Set messages envelope
     *
     * @return Illuminate\Mail\Mailables\Envelope;
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('forex@example.com', 'no-replay'),
            subject: 'Your order of foreign currency',
        );
    }

    /**
     * Email content
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.order',
        );
    }



    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.order');
    }
}
