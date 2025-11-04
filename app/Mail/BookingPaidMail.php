<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class BookingPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        $pdf = \PDF::loadView('emails.nota_pdf', ['booking' => $this->booking]);

        return $this->subject('Terima kasih, booking Anda telah diterima!')
                    ->markdown('emails.booking_paid')
                    ->attachData($pdf->output(), 'nota-booking.pdf');
    }
}
