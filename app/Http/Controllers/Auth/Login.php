<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Mail\SendEmailVerification;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Traits\PubFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Login extends BaseController
{
    use PubFunctions;
    //landing page
    public function landingPage()
    {
        $web = GeneralSetting::find(1);

        return view('auth.login')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Account Login'
        ]);
    }
    //process login request
    public function processLogin(Request $request)
    {
        try {
            $web = GeneralSetting::find(1);

            $validator = Validator::make($request->all(),[
                'email'=>['required','email','exists:users,email'],
                'password'=>['required','string'],
                'remember'=>['nullable','integer'],
            ])->stopOnFirstFailure();

            if ($validator->fails()){
                return $this->sendError('validation.error',['error'=>$validator->errors()->all()]);
            }

            $input = $validator->validated();

            $user = User::where('email',$input['email'])->first();

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

                return $this->sendError('email.error',[
                    'error'=>'Email account is unverified. Use the link in the mail sent to your email to verify your account.'
                ]);
            }

            if ($user->lognizer==1){
                return $this->loginWithLimiter($request,$input,$user,$web);
            }else{
                return $this->loginWithoutLimiter($request,$input,$user,$web);
            }

        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while procesisng account login: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
    //Lock user account
    public function lockUserAccount(Request $request,$user)
    {
        if (! $request->hasValidSignature()) {
            return redirect()->to(route('home.index'))
                ->with('error','Invalid email verification signature.');
        }
        $user = User::where('reference',$user)->first();
        //check against if it's verified
        if ($user->status==3){
            return redirect()->to(route('login'))->with('error','Account is already locked');
        }

        $user->status=3;
        $user->save();

        return redirect()->to(route('login'))->with('success','Account successfully locked');
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower(request('email')) . '|' . request()->ip();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     *
     */
    public function checkTooManyFailedAttempts($user,$web)
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $user->loginNumber)) {
            return false;
        }
        $seconds = RateLimiter::availableIn($this->throttleKey());

        //block user account if user allows it
        if ($user->lognizerAction==1){
            $timeUnblock = ($user->lognizerTime/60).' Minutes';
            $user->status=2;
            $user->timeUnblock = strtotime($timeUnblock,time());
            $user->save();
            //we will attempt to send notification if user allows it
            $message = "
                An unidentified login attempt from ".request()->ip()." was recently blocked by the
                ".$web->lognizerName." active on your account, and your selected action - <b>Block</b> has
                been applied to your account. Your account will be unblocked by
                <b>".date('d-m-Y h:i:s a',$user->timeUnblock)."</b>
            ";
            $this->sendUserNotification($user,'Login Attempt blocked',$message);
        }
        return $this->sendError('rate.error',[
            'error'=>'Too many login attempts. You have been banned. You will be able to attempt again in '.$seconds.' second.'
        ]);
    }
    //process login with login limiter
    public function loginWithLimiter($request,$input,$user,$web)
    {
        //check if there was a banning.
         if ($this->checkTooManyFailedAttempts($user,$web)){
             return $this->checkTooManyFailedAttempts($user,$web);
         }

        //let us check something real quick
        if (Auth::once(['email'=>$input['email'],'password'=>$input['password']])){

            if ($user->status !=1){
                return $this->sendError('account.error',['error'=>'Account is not activated.']);
            }

            RateLimiter::clear($this->throttleKey());//clear everything

            //check if two-factor is active
            if ($user->twoFactor==1){
                //store a session, which we will use the next time
                $request->session()->put('user',$user->email);
                $request->session()->put('remember',$request->filled('remember'));
                return $this->sendResponse([
                    'redirectTo'=>'',
                    'showOtp'=>true
                ],'Enter the OTP in your authenticator');

            }else{

                $this->notifyUserOfLogin($user,$request,$web);
                Auth::login($user,$request->filled('remember'));

                return $this->sendResponse([
                    'redirectTo'=>route('user.dashboard'),
                    'showOtp'=>false
                ],'Login successful. Redirecting soon ...');
            }
        }else{

            RateLimiter::hit($this->throttleKey(), $user->lognizerTime);//increment

            $attemptLeft = RateLimiter::remaining($this->throttleKey(), $user->loginNumber);//get attempts left


            return $this->sendError('authentication.error',[
                'error'=>'Invalid password or email. You have '.$attemptLeft.' attempts left.'
            ]);
        }
    }
    //process login without limiter
    public function loginWithoutLimiter($request,$input,$user,$web)
    {
        //let us check something real quick
        if (Auth::once([
            'email'=>$input['email'],
            'password'=>$input['password']])
        ){

            if ($user->status !=1){
                return $this->sendError('account.error',['error'=>'Account is not activated.']);
            }


            //check if two-factor is active
            if ($user->twoFactor==1){
                //store a session, which we will use the next time
                $request->session()->put('user',$user->email);
                $request->session()->put('remember',$request->filled('remember'));

                return $this->sendResponse([
                    'redirectTo'=>'',
                    'showOtp'=>true
                ],'Enter the OTP in your authenticator');

            }else{

                $this->notifyUserOfLogin($user,$request,$web);
                Auth::login($user,$request->filled('remember'));

                return $this->sendResponse([
                    'redirectTo'=>route('user.dashboard'),
                    'showOtp'=>false
                ],'Login successful. Redirecting soon ...');
            }
        }else{

            return $this->sendError('authentication.error',[
                'error'=>'Invalid password or email.'
            ]);
        }
    }
}
