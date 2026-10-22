<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class AdminLoginCode extends Mailable
{
    public function __construct(public string $code) {}

    public function build(): static
    {
        return $this->subject('Your SmartParking admin login code')->text('emails.admin-login-code');
    }
}
