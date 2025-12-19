<?php

namespace App\Jobs;

use App\Mail\NotifyMail;
use App\Models\Guardian;
use App\Models\NotificationLog;
use App\Notifications\SendNotification;
use App\Notifications\SendPushNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessGuardianNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $guardian, $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Guardian $guardian, $data)
    {
        $this->guardian = $guardian;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendNotificationToGuardian();
    }

    /**
     * @return void
     */
    private function sendNotificationToGuardian(): void
    {
        if ($this -> data[ 'type' ] == 'SMS') {
            $api_response = sendOTPCode($this -> data['message'], $this->guardian -> mobile);
            Log::info('(---SMS--) Log Successfully created at ' . Carbon::now() . '. Here is the API Response :' . $api_response);

            // $guardian->notify(new SendPushNotification('Notification', $message));
        }

        if ($this -> data[ 'type' ] == 'Push Notification') {
            $this->guardian -> notify(new SendPushNotification('Notification', $this -> data['message']));
            Log::info('(---Push Notification--) Log Successfully created at ' . Carbon::now());
        }

        if ($this -> data[ 'type' ] == 'Email') {
            // $notification = new SendNotification($this -> data['header'], $this -> data['message'], $this -> data['salutation'],$this->guardian);
            // $this->guardian->notify($notification);
            $subject = $this->data['header'];
            $receiver_name = $this->guardian->guardian_name;
            $message = $this->data['message'];
            Mail::to($this->guardian->email)->send(new NotifyMail($subject, $receiver_name, $message));
            Log::info('(---Email--) Log Successfully created at ' . Carbon::now());
        }

        if ($this -> data[ 'type' ] == 'SMS, Email, Push Notification') {
            sendOTPCode($this -> data['message'], $this->guardian -> mobile);
            Log::info('(---SMS--) Log Successfully created at ' . Carbon::now() . '. Here is the API Response :' . $api_response);

            // $notification = new SendNotification($this -> data['header'], $this -> data['message'], $this -> data['salutation'],$this->guardian);
            // $this->guardian->notify( $notification);
            $subject = $this->data['header'];
            $receiver_name = $this->guardian->guardian_name;
            $message = $this->data['message'];

            Mail::to($this->guardian->email)->send(new NotifyMail($subject, $receiver_name, $message));
            Log::info('(---Email--) Log Successfully created at ' . Carbon::now());

            $this->guardian -> notify(new SendPushNotification('Notification', $this -> data['message']));
            Log::info('(---Push Notification--) Log Successfully created at ' . Carbon::now());
        }

        NotificationLog ::create([
            'guardian_id' => $this->data['guardian_id'],
            'notification_type' => $this -> data[ 'type' ],
            'notification_body' => $this -> data['message'],
            'status' => 'sent',
            'system_notification_id' => $this -> data['system_notification_id']
        ]);
    }
}
