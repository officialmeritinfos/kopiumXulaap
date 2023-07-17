<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Mail\SendEmailVerification;
use App\Mail\SendPasswordChanged;
use App\Mail\SendRecoverPassword;
use App\Models\GeneralSetting;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PasswordResets extends BaseController
{
    //landing page
    public function landingPage()
    {
        $web = GeneralSetting::find(1);

        return view('auth.recover_password')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Password Recovery'
        ]);
    }
    //process email authentication
    public function processPasswordReset(Request $request)
    {
        try {
            $web = GeneralSetting::find(1);

            $validator = Validator::make($request->post(),[
                'email'=>['required','email','exists:users,email']
            ])->stopOnFirstFailure();

            if ($validator->fails()){
                return $this->sendError('validation.error',['error'=>$validator->errors()->all()]);
            }
            $input = $validator->validated();
            $user = User::where('email',$input['email'])->first();

            if ($user->status !=1){
                return $this->sendError('account.error',['error'=>'Account is not activated']);
            }
            //check account is verified
            if ($user->emailVerified!=1){
                //let's send email verification
                $mailData = [
                    'name'=>$user->name,
                    'fromMail'=>$web->noreply,
                    'siteName'=>$web->name,
                    'user'=>$user,
                    'supportMail'=>$web->supportEmail
                ];
                Mail::to($user->email)->send(new SendEmailVerification($mailData));

                return $this->sendError('account.error',[
                    'error'=>'Email is not verified. Check your spambox too.'
                ]);
            }

            //send email for password recovery
            $mailData = [
                'name'=>$user->name,
                'fromMail'=>$web->noreply,
                'siteName'=>$web->name,
                'user'=>$user,
                'supportMail'=>$web->supportEmail
            ];

            Mail::to($user->email)->send(new SendRecoverPassword($mailData));

            return $this->sendResponse([
                'redirectTo'=>route('recoverPassword')
            ],'An instruction has been sent to your mail.');

        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while recovering account: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
    //process token and show landing page
    public function resetPassword(Request $request, $ref,$token)
    {
        try {
            if (! $request->hasValidSignature()) {
                return redirect()->to(route('home.index'))
                    ->with('error','Invalid email verification signature.');
            }
            $user = User::where('reference',$ref)->first();

            $web = GeneralSetting::find(1);

            return view('auth.reset_password')->with([
                'web'=>$web,
                'siteName'=>$web->name,
                'pageName'=>'Reset Password',
                'token'=>$token,
                'email'=>$user->email
            ]);

        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while verifying password reset email: ' . $exception->getMessage());
            return to_route('recoverPassword')
                ->with('error','A server error occurred while verifying your token signature.');
        }
    }
    //process password reset
    public function processPasswordResetRequest(Request $request)
    {
        try {
            $web=GeneralSetting::find(1);
            $validator = Validator::make($request->all(),[
                'token'=>[
                    'required',
                    Rule::exists('password_resets','token')->where('email',$request->email)
                ],
                'email'=>[
                    'required','email','exists:users,email'
                ],
                'password'=>[
                    'required','confirmed',
                    Password::min(8)->mixedCase()->uncompromised(2)
                ],
                'password_confirmation'=>[
                    'required','same:password'
                ]
            ])->stopOnFirstFailure();

            if ($validator->fails()){
                return $this->sendError('validation.error',['error'=>$validator->errors()->all()]);
            }
            $input = $validator->validated();

            $user = User::where('email',$input['email'])->first();

            $user->password = bcrypt($input['password']);
            $user->save();

            //send mail about the change of password
            $mailData = [
                'name'=>$user->name,
                'fromMail'=>$web->noreply,
                'siteName'=>$web->name,
                'user'=>$user,
                'supportMail'=>$web->supportEmail
            ];

            PasswordReset::where('email',$user->email)->delete();

            Mail::to($user->email)->send(new SendPasswordChanged($mailData));

            return $this->sendResponse([
                'redirectTo'=>route('login')
            ],'Password successfully changed.');

        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while reseting email: ' . $exception->getMessage());

            return $this->sendError('server.error',['error'=>'Internal server error  occurred']);
        }
    }
}
