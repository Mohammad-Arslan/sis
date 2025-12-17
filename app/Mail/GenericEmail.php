<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject, $template, $name, $fromEmail, $message, $documents;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $template, $name, $fromEmail, $message, $documents)
    {
        $this->subject = $subject;
        $this->template = $template;
        $this->name = $name;
        $this->fromEmail = $fromEmail;
        $this->message = $message;
        $this->documents = $documents; //never use attachments as name, bcz Mailable class already has property $attachments.
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = ['subject' => $this->subject, 'message' => $this->message];

        $email = $this->subject($this->subject);

        if ($this->fromEmail && $this->name)
            $email = $email->from($this->fromEmail, $this->name);

        $email = $email->view($this->template, ['data' => $data]);

        if (!empty($this->documents)) {
            if(is_array($this->documents)){
                foreach ($this->documents as $document) {
                    $email->attach($document);
                }
            }
            else{
                $email->attach($this->documents);
            }
        }
        return $email;
    }
}
