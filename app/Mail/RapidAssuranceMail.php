<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 

class RapidAssuranceMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
 
    public function __construct($user)
    {
        //
        $this->user = $user;
    }
 
    public function build()
    {
        $subject = 'OTP for PayU MSS';
        return $this->view('emails.login_otp')->subject($subject);
    }
}
