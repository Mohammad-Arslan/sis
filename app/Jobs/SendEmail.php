<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\GenericEmail;
use Illuminate\Support\Facades\Mail;

/*implements ShouldQueue*/
class SendEmail
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 5;
    public $timeout = 100;

    protected $details, $subject ,$template, $name, $from, $bcc;
    protected $message, $documents;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
        $this->subject = isset($this->details['subject']) && ! empty($this->details['subject']) ? $this->details['subject'] : '';
        $this->template = isset($this->details['template']) && ! empty($this->details['template']) ? $this->details['template'] : '';
        $this->name = isset($this->details['name']) && ! empty($this->details['name']) ? $this->details['name'] : 'Ozoned Digital';
        $this->from = isset($this->details['from']) && ! empty($this->details['from']) ? $this->details['from'] : '';
        $this->message = isset($this->details['message']) && ! empty($this->details['message']) ? $this->details['message'] : '';
        $this->documents = isset($this->details['documents']) && ! empty($this->details['documents']) ? $this->details['documents'] : [];
        $this->bcc = isset($this->details['bcc']) && ! empty($this->details['bcc']) ? $this->details['bcc'] : null;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new GenericEmail($this->subject, $this->template, $this->name, $this->from, $this->message, $this->documents);

        foreach ($this->details['email'] as $emailAddress) {
            $generateEmail = Mail::to($emailAddress);

            if (isset($this->bcc)) {
                $generateEmail = $generateEmail->bcc($this->bcc);
            }

            $generateEmail->send($email);
        }
    }
}
