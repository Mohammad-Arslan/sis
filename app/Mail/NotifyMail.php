<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $subject, $receiver_name, $fromEmail, $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $receiver_name, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->receiver_name = $receiver_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = ['subject' => $this->subject, 'message' => $this->message];
        $address = 'sysgen@ucs.edu.pk';
        $name = 'SYSGEN';
       // $body = ['receiver_name' => $this->receiver_name,'message' => $this->message];
        return $this->view('email_templates.notifications_email', ['receiver_name' => $this->receiver_name,'body' => $this->message])
                    ->from($address, $name)
                    // ->cc($address, $name)
                    // ->bcc($address, $name)
                    // ->replyTo($address, $name)
                    ->subject($this->subject);
    }
}
