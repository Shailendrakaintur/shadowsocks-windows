<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class verification extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $address = 'mmahantesh1410@gmail.com';
        $subject = 'This is a demo!';
        $name = 'Beumont';
   
        return $this->view('emails.verification')
                    ->from($address, $name)
                    ->cc($address, $name)
                    ->bcc($address, $name)
                    ->replyTo($address, $name)
                    ->subject($this->data['subject'])
                    ->with([ 'test_greetings' => $this->data['greetings'] ])
                    ->with([ 'test_message' => $this->data['message'] ]);

    }
}