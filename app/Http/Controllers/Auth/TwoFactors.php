<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Traits\PubFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FA\Google2FA;

class TwoFactors extends BaseController
{
    use PubFunctions;
    //process two-factor authentication
    public function processTwoFactor(Request $request)
    {
        try {
            $web = GeneralSetting::find(1);

            $validator = Validator::make($request->all(),[
                'one_time_password'=>['required','numeric','digits:6']
            ])->stopOnFirstFailure();

            if ($validator->fails()){
                return $this->sendError('validation.error',['error'=>$validator->errors()->all()]);
            }

            $userEmail  = session('user');
            $remember  = session('remember');

            $user = User::where('email',$userEmail)->first();

            if (empty($user)){
                return $this->sendError('authentication.error',[
                    'error'=>'Something went wrong. We could not find this profile.'
                ]);
            }

            $input = $validator->validated();

            $code = $input['one_time_password'];

            $google2fa = new Google2FA();

            $valid = $google2fa->verify($code,$user->google2fa_secret);

            if ($valid){
                $this->notifyUserOfLogin($user,$request,$web);
                Auth::login($user,$remember);

                return $this->sendResponse([
                    'redirectTo'=>route('user.dashboard')
                ],'Login successful. Redirecting soon ...');

            }else{
                return $this->sendError('authentication.error',[
                    'error'=>'Invalid token supplied.'
                ]);
            }
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while authenticating Token: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
}
