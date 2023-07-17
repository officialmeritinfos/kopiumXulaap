<?php

namespace App\Mail;

use App\Models\GeneralSetting;
use App\Models\PasswordReset;
use App\Traits\PubFunctions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class SendRecoverPassword extends Mailable
{
    use Queueable, SerializesModels,PubFunctions;
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
            subject: 'Password Recovery on '.$this->mailData['siteName'],
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

        $token = $this->generateUniqueReference('password_resets','token',40);

        PasswordReset::create([
            'email'=>$user->email,
            'token'=>md5($token)
        ]);

        $url = URL::temporarySignedRoute(
            'verify.password.reset', now()->addHour(), ['user' => $user->reference,'token'=>md5($token)]
        );

        return new Content(
            view: 'emails.recover_password',
            with: [
                'web'=>$web,
                'siteName'=>$web->name,
                'user'  =>$user->name,
                'supportMail'=>$web->supportMail,
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
