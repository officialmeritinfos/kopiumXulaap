<?php

namespace App\Notifications;

use App\Models\UserSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomNotification extends Notification
{
    use Queueable;
    protected mixed $user,$title,$message,$url,$ending;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$title,$message,$ending=null,$url=null,)
    {
        $this->user = $user;
        $this->message = $message;
        $this->title = $title;
        $this->url = $url;
        $this->ending = $ending;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = empty($this->url)?route('login'):$this->url;
        return (new MailMessage)
                    ->subject($this->title)
                    ->greeting('Hello '.$this->user->name)
                    ->line($this->message)
                    ->action('Go to account', $url)
                    ->line($this->ending);
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
