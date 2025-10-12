<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LocationSharingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $locationUrl;
    public $vendorName;

    public function __construct($locationUrl, $vendorName)
    {
        $this->locationUrl = $locationUrl;
        $this->vendorName = $vendorName;
    }

    public function build()
    {
        return $this->subject('Delivery Location - ' . $this->vendorName)
                    ->view('emails.location-sharing');
    }
}
