<?php

namespace App\Mail;

use App\Models\GeneralSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPasswordChanged extends Mailable
{
    use Queueable, SerializesModels;
    public mixed $mailData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address($this->mailData['fromMail'],$this->mailData['siteName']),
            replyTo:[
                $this->mailData['supportMail']
            ] ,
            subject: 'Password Reset on '.$this->mailData['siteName'],
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $web = GeneralSetting::find(1);
        //let us process email verification link
        $user = $this->mailData['user'];

        return new Content(
            view: 'emails.password_changed',
            with: [
                'web'=>$web,
                'siteName'=>$web->name,
                'user'  =>$user->name,
                'supportMail'=>$web->supportMail,
                'link'=>route('login')
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
