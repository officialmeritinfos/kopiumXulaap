<?php

namespace App\Http\Controllers\Dashboard\Users;


use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Fiat;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserSetting;
use App\Traits\PubFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PragmaRX\Google2FA\Google2FA;

class Home extends BaseController
{
    use PubFunctions;
    public function landingPage()
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        if ($user->twoFactor!=1){
            $google2fa = app('pragmarx.google2fa');

            $QR_Image = $google2fa->getQRCodeInline(
                $web->name,
                $user->email,
                $user->google2fa_secret
            );
            $secret = $user->google2fa_secret;
        }else{
            $QR_Image = '';
            $secret='';
        }

        return view('dashboard.users.pages.home')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Overview',
            'user'=>$user,
            'qrCode'=> $QR_Image,
            'secret'=>$secret,
        ]);
    }
    //complete profile
    public function setupProfile()
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        if($user->completedProfile ==1){
            return back()->with('error','Profile already completed');
        }

        return view('dashboard.users.pages.set_profile')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Set up account',
            'user'=>$user,
            'countries'=>Country::where('status',1)->get(),
            'fiats'=>Fiat::where('status',1)->get()
        ]);
    }
    //verify authenticator setup
    public function verifyAuthenticatorSetup(Request $request)
    {
        try {
            $web = GeneralSetting::find(1);
            $user = Auth::user();
            $validator = Validator::make($request->all(),[
                'one_time_password'=>['required','numeric','digits:6']
            ])->stopOnFirstFailure();

            if ($validator->fails()){
                return $this->sendError('validation.error',['error'=>$validator->errors()->all()]);
            }

            $input = $validator->validated();
            $code = $input['one_time_password'];

            $google2fa = new Google2FA();

            $valid = $google2fa->verify($code,$user->google2fa_secret);

            if ($valid){

                $user->twoFactor = 1;
                $user->save();

                $message = "Account two-factor authentication was activated";
                $this->sendUserNotification($user,'Two-Factor Authentication Setup',$message);

                return $this->sendResponse([
                    'redirectTo'=>url()->previous()
                ],'Two-factor authentication setup completed');
            }
            return $this->sendError('authentication.error',['error'=>'Wrong OTP entered.']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while setting up 2FA: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
    //submit profile update
    public function updateProfile(Request $request)
    {
        try {
            $web = GeneralSetting::find(1);
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'country'=>['required','alpha',Rule::exists('countries','iso3')->where('status',1)],
                'currency'=>['required','alpha',Rule::exists('fiats','code')->where('status',1)],
                'address'=>['required','string'],
                'phone'=>['required','numeric'],
                'city'=>['required','string','max:150'],
                'state'=>['nullable','string','max:150'],
                'zip'=>['required','string','max:150'],
            ])->stopOnFirstFailure();

            if ($validator->fails()) {
                return $this->sendError('validation.error', ['error' => $validator->errors()->all()]);
            }

            $input = $validator->validated();

            $country = Country::where('iso3',$input['country'])->first();
            if (empty($country)){
                return $this->sendError('residence.error',['error'=>'Invalid country selected']);
            }
            if (User::where('id',$user->id)->update([
                'country'=>$country->name,
                'countryCode'=>$country->iso3, 'address'=>$input['address'],
                'city'=>$input['city'], 'zipCode'=>$input['zip'],'state'=>$input['state'],
                'phoneCode'=>ltrim(ltrim($country->phonecode,'-'),'+'),
                'phone'=>ltrim($input['phone'],0),
                'accountCurrency'=>$input['currency'],'completedProfile'=>1
            ])){
                return $this->sendResponse(['redirectTo'=>route('user.dashboard')],'Profile updated');
            }
            return $this->sendError('profile.error',['error'=>'We are unable to update your profile. Please try again']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while setting profile: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
    //submit profile pic update
    public function updateProfilePic(Request $request)
    {
        try {
            $web = GeneralSetting::find(1);
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'profilePic'=>['required','image','max:3000'],
            ])->stopOnFirstFailure();

            if ($validator->fails()) {
                return $this->sendError('validation.error', ['error' => $validator->errors()->all()]);
            }

            //check if the icon is uploaded
            if ($request->hasFile('profilePic')) {
                $result = $request->file('profilePic')->storeOnCloudinary('userProfile');
                $icon = $result->getPath();
            } else {
                $icon = $user->profilePhoto;
            }

            $user->profilePhoto = $icon;
            $user->save();
            return $this->sendResponse([
                'redirectTo'=>url()->previous()
            ],'Profile photo uploaded');

        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while uploading photo: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
    //logout
    public function logout(Request $request)
    {
        $user = Auth::user();

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return to_route('login')->with('success','Successfully logged out.');
    }

}
