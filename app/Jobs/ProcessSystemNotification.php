<?php

namespace App\Jobs;

use App\Mail\NotifyMail;
use App\Models\NotificationLog;
use App\Models\User;
use App\Notifications\SendNotification;
use App\Notifications\SendPushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessSystemNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $data, $user;

    /**
     * Create a new job instance.
     *
     * @param $data
     * @param User $user
     */
    public function __construct(User $user, $data)
    {
        $this -> data = $data;
        $this -> user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this -> sendNotificationToUser();
    }

    /*
     * @return void
     */
    protected function sendNotificationToUser(): void
    {
        if ($this -> data[ 'type' ] == 'SMS') {
            sendOTPCode($this -> data[ 'message' ], $this -> data[ 'mobile' ]);
        }

        /*if ( $this -> data[ 'type' ] == 'Push Notification' ) {
            $this -> user -> notify(new SendPushNotification('Notification', $this -> data[ 'message' ]));
        }*/

        if ($this -> data[ 'type' ] == 'Email') {
            // $notification = new SendNotification($this -> data[ 'header' ], $this -> data[ 'message' ], $this -> data[ 'salutation' ],$this->user);
            // $this->user->notify($notification);
            $subject = $this->data['header'];
            $receiver_name = $this->user->name;
            $message = $this->data['message'];
            // dd($subject,  $receiver_name, $message, $this->user->email);
            Mail::to($this->user->email)->send(new NotifyMail($subject, $receiver_name, $message));
        }

        if ($this -> data[ 'type' ] == 'SMS, Email, Push Notification') {
            sendOTPCode($this -> data[ 'message' ], $this -> data[ 'mobile' ]);
            /*$this -> user -> notify(new SendPushNotification('Notification', $this -> data[ 'message' ]));*/
            // $notification = new SendNotification($this -> data[ 'header' ], $this -> data[ 'message' ], $this -> data[ 'salutation' ],$this->user);
            // $this->user->notify($notification);
            $subject = $this->data['header'];
            $receiver_name = $this->user->name;
            $message = $this->data['message'];
            Mail::to($this->user->email)->send(new NotifyMail($subject, $receiver_name, $message));
        }

        NotificationLog ::create([
            'employee_id' => $this -> data[ 'employee_id' ],
            'notification_type' => $this -> data[ 'type' ],
            'notification_body' => $this -> data[ 'message' ],
            'status' => 'sent',
            'system_notification_id' => $this -> data[ 'system_notification_id' ],
        ]);
    }
}
