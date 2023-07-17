<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmail extends Notification
{
    use Queueable;
    public $name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        //
        $this->name = $name;
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
        $url = route('login');
        return (new MailMessage)
            ->subject('Welcome to '.env('APP_NAME'))
            ->greeting('Welcome '.$this->name)
            ->line('Welcome to '.env('APP_NAME').', your one stop solution for buying and transacting data and airtime.
                I am Michael,the CEO of '.env('APP_NAME').'.')
            ->line('With '.env('APP_NAME').' you can easily sell your over recharged airtime and get money in return.
                <p>Not Just that, you can also pay your other bills and send money to your family and friends.</p>
                ')
            ->line('We are open to feature request and we will keep you updated once we release your requested features.')
            ->action('Go to Dashboard', $url)
            ->line('Thank you for choosing '.env('APP_NAME'));
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
