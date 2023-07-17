<?php

namespace App\Traits;

use App\Custom\Paystack;
use App\Mail\SendLoginNotification;
use App\Models\Login;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserSetting;
use App\Notifications\AdminMailNotification;
use App\Notifications\CustomNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

trait PubFunctions
{
    //generate unique alpha-numeric Id
    public function generateUniqueReference($table,$column,$length=10): string
    {
        $reference = $this::generateRef($length);
        return DB::table($table)->where($column,$reference)->first()?$this->generateUniqueReference($table,$column,$length):$reference;
    }
    //generate unique numeric ID
    public function generateUniqueId($table,$column,$length=10): string
    {
        $id = $this::generateId($length);
        return DB::table($table)->where($column,$id)->first()?$this->generateUniqueId($table,$column,$length):$id;
    }
    //generate 6-code token
    public function generateToken($table,$column): int
    {
        $reference = $this::createCode();
        return DB::table($table)->where($column,$reference)->first() ?
            $this->generateToken($table,$column):$reference;
    }
    //generate numeric ID
    private function generateId($length=10): string
    {
        $id = mt_rand();
        return Str::padLeft($id,$length,'0');
    }
    //generate alpha-numeric id
    private function generateRef($length=10): string
    {
        return Str::random($length);
    }
    //generate unique six code for verification purposes
    private static function createCode(): int
    {
        return rand(100000,999999);
    }
    //get the current time in Date-time string
    public function getCurrentDateTimeString(): string
    {
        return Carbon::now()->toDateTimeString();
    }
    //send admin mail
    public function sendAdminMail($message,$title)
    {
        $admin = User::where('isAdmin',1)->first();
        if (!empty($admin)){
            $admin->notify(new AdminMailNotification($admin,$title,$message));
        }
    }
    //notify user of login
    public function notifyUserOfLogin($user,$req,$web)
    {
        //send notification if activated by user
        $settings = UserSetting::where('user',$user->id)->first();

        $agent = new Agent();

        Login::create([
            'user'=>$user->id,
            'device'=>$agent->device().'; Version: '.$agent->version($agent->device()),
            'platform'=>$agent->platform().'; Version: '.$agent->version($agent->platform()),
            'ipAddress'=>$req->ip(),
            'browser'=>$agent->browser().'; Version: '.$agent->version($agent->browser())
        ]);

        if (!empty($settings)){
            if ($settings->accountLogin==1){
                $mailData = [
                    'name'=>$user->name,
                    'fromMail'=>$web->noreply,
                    'siteName'=>$web->name,
                    'user'=>$user,
                    'supportMail'=>$web->supportEmail
                ];

                Mail::to($user->email)->send(new SendLoginNotification($mailData));
            }
        }
    }
    //send notification to user
    public function sendUserNotification($user,$title,$message,$send=true)
    {
        UserNotification::create([
            'user'=>$user->id,'title'=>$title,
            'content'=>$message,'status'=>2
        ]);
        if ($send==true){
            //send mail
            $settings = UserSetting::where('user',$user->id)->first();
            if (!empty($settings)){
                if ($settings->accountNotification==1){

                    $messageToSend = "
                    A new activity just took place on your account. Details are below:<br/>
                    <hr/>
                    <p>".$message."</p>
                ";

                    $user->notify(new CustomNotification($user,$title,$messageToSend));
                }
            }
        }
    }
}
