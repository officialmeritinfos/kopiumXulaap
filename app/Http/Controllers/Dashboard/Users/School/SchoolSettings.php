<?php

namespace App\Http\Controllers\Dashboard\Users\School;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\UserSchool;
use App\Models\UserSchoolBranch;
use App\Traits\PubFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolSettings extends BaseController
{
    use PubFunctions;
    //landing page
    public function landingPage($slug)
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->firstOrFail();


        return view('dashboard.users.pages.schools.settings.index')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Manage '.$school->name.' setups',
            'user'=>$user,
            'school'=>$school,
            'branches'=>UserSchoolBranch::where('school',$school->id)->paginate(15)
        ]);
    }
}
