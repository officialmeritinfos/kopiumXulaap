<?php

namespace App\Http\Controllers\Dashboard\Users\School;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Fiat;
use App\Models\GeneralSetting;
use App\Models\UserSchool;
use App\Traits\PubFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Schools extends BaseController
{
    use PubFunctions;
    //list all schools
    public function landingPage()
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();


        return view('dashboard.users.pages.schools.list')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Manage Schools',
            'user'=>$user,
            'countries'=>Country::where('status',1)->get(),
            'fiats'=>Fiat::where('status',1)->get(),
            'schools'=>UserSchool::where('user',$user->id)->paginate(15),
        ]);
    }
    //create new school
    public function processAdd(Request $request)
    {
        try {
            $web = GeneralSetting::find(1);
            $user = Auth::user();
            $validator = Validator::make($request->all(),[
                'schoolIcon'=>['nullable','image','max:1000'],
                'name'=>['required','string','max:150','unique:user_schools,name'],
                'slug'=>['required','string','max:150','unique:user_schools,slug'],
                'tagline'=>['nullable','string','max:150'],
                'about'=>['required','string'],
                'country'=>['required','alpha',Rule::exists('countries','iso3')->where('status',1)],
                'currency'=>['required','alpha',Rule::exists('fiats','code')->where('status',1)],
                'address'=>['required','string'],
                'phone'=>['required','numeric'],
                'city'=>['required','string','max:150'],
                'state'=>['nullable','string','max:150'],
                'email'=>['nullable','string','max:150'],
            ])->stopOnFirstFailure();

            if ($validator->fails()) {
                return $this->sendError('validation.error', ['error' => $validator->errors()->all()]);
            }

            $input = $validator->validated();

            //check if the icon is uploaded
            if ($request->hasFile('schoolIcon')) {
                $result = $request->file('schoolIcon')->storeOnCloudinary('school-profile');
                $icon = $result->getPath();
            } else {
                $icon = '';
            }
            //let us properly slug the slug
            $slug = Str::slug($input['slug']);
            $slugExists = UserSchool::where('slug',$slug)->first();
            if (!empty($slugExists)){
                return $this->sendError('slug.error',['error'=>'Url already exists. Please try another one']);
            }
            $reference = $this->generateUniqueReference('user_schools','reference',7);
            $school = UserSchool::create([
                'user'=>$user->id,'reference'=>$reference,
                'about'=>clean($input['about']),'name'=>$input['name'],
                'slug'=>$slug,'logo'=>$icon,'country'=>$input['country'],
                'state'=>$input['state'],'city'=>$input['city'],
                'address'=>$input['address'],'currency'=>$input['currency'],
                'email'=>$input['email'],'phone'=>ltrim($input['phone'],0)
            ]);
            if (!empty($school)){
                return $this->sendResponse([
                    'redirectTo'=>url()->previous()
                ],'Institution successfully added.');
            }
            return $this->sendError('school.error',['error'=>'Unable to add institution']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while creating school: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
    //school detail
    public function schoolDetail($slug)
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->firstOrFail();


        return view('dashboard.users.pages.schools.details')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Manage Schools',
            'user'=>$user,
            'school'=>$school
        ]);
    }

}
