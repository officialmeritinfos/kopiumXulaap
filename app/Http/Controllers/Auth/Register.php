<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Mail\SendEmailVerification;
use App\Mail\SendWelcomeEmail;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Notifications\WelcomeEmail;
use App\Traits\PubFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use PragmaRX\Google2FA\Google2FA;

class Register extends BaseController
{
    use PubFunctions;
    //landing page
    public function landingPage()
    {
        $web = GeneralSetting::find(1);

        return view('auth.register')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Sign Up'
        ]);
    }
    //process registration
    public function processRegistration(Request $request)
    {
        try {
            $web = GeneralSetting::find(1);
            $validator = Validator::make($request->all(),[
                'name'      =>[
                    'required','string','max:200'
                ],
                'email'      =>[
                    'required','string','max:200','unique:users,email'
                ],
                'password'      =>[
                    'required',Password::min(8)->mixedCase()->uncompromised(2),'confirmed'
                ],
                'password_confirmation'      =>[
                    'required','same:password'
                ],
            ])->stopOnFirstFailure();

            if ($validator->fails()) {
                return $this->sendError('validation.error',['error'=>$validator->errors()->all()]);
            }

            $input = $validator->validated();

            $reference = $this->generateUniqueId('users','reference',15);

            //let us initialize two-factor authentication
            $google2fa = new Google2FA();

            $input['google2fa_secret']= $google2fa->generateSecretKey();

            //create a user
            $user = User::create([
                'name'=>$input['name'],'email'=>$input['email'],'password'=>bcrypt($input['password']),
                'reference'=>$reference,'twoFactor'=>$web->twoFactor,'emailVerified'=>$web->emailVerification,
                'accountType'=>0,'google2fa_secret'=>$input['google2fa_secret']
            ]);
            if (!empty($user)){
                if ($user->emailVerified!=1){
                    //send email verification
                    $mailData = [
                        'name'=>$user->name,
                        'fromMail'=>$web->noreply,
                        'siteName'=>$web->name,
                        'user'=>$user,
                        'supportMail'=>$web->supportEmail
                    ];
                    Mail::to($user->email)->send(new SendEmailVerification($mailData));

                    $response = "We just sent you a mail for account activation.";
                }else{
                    $user->notify(new WelcomeEmail($user->name));
                    $response = "Thanks for joining ".$web->name.". Please login to proceed.";
                }
                $adminMessage = "A new registration just took place on ".$web->name.". Name of New registrant is ".$input['name']."
                 and reference Id ".$reference.".
                ";
                //send admin email
                $this->sendAdminMail($adminMessage,'New Account Registration on '.$web->name);

                return $this->sendResponse([
                    'redirectTo'=>route('login')
                ],$response);
            }
            return $this->sendError('registration.error',['error'=>'Something went wrong. Please try again']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while creating account: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
    //process email verification
    public function emailVerification(Request $request,$user)
    {
        if (! $request->hasValidSignature()) {
            return redirect()->to(route('home.index'))
                ->with('error','Invalid email verification signature.');
        }
        $user = User::where('reference',$user)->first();
        //check against if it's verified
        if ($user->emailVerified==1){
            return redirect()->to(route('login'))->with('error','Email already verified.');
        }

        $user->emailVerified=1;
        $user->save();
        $user->markEmailAsVerified();
        //send welcome email
        $user->notify(new WelcomeEmail($user->name));

        return redirect()->to(route('login'))->with('success','Email successfully verified.');
    }
}
