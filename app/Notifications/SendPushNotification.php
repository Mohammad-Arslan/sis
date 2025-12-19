<?php

namespace App\Notifications;

use App\Models\FcmToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kutia\Larafirebase\Messages\FirebaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPushNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['firebase'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the firebase representation of the notification.
     */


    public function toFirebase($notifiable)
    {
        $deviceTokens = FcmToken::where('guardian_id', $notifiable->id)->pluck('device_key')->toArray();
        $this->title = 'Hey, ' . $notifiable->guardian_name;

        // SystemNotification::create([
        //     'user_id' => $notifiable->id,
        //     'title' => $this->title,
        //     'description' => $this->message,
        //     'status' => 'sent'
        // ]);

        return (new FirebaseMessage())
            ->withTitle($this->title)
            ->withBody($this->message)
            ->asNotification($deviceTokens); // OR ->asMessage($deviceTokens);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
