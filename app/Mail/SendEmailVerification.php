<?php

namespace App\Mail;

use App\Models\GeneralSetting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class SendEmailVerification extends Mailable
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
            subject: 'Verify your Email on '.$this->mailData['siteName'],
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

        $url = URL::temporarySignedRoute(
            'verify.email.link', now()->addDay(), ['user' => $user->reference]
        );

        return new Content(
            view: 'emails.email_verification',
            with: [
                'web'=>$web,
                'siteName'=>$web->name,
                'link'=>$url
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
