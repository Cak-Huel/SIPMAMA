<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatusPendaftaranMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; // Data untuk dikirim ke view

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Update Status Pendaftaran Magang - BPS Lamongan')
            ->view('emails.status');
    }
}
