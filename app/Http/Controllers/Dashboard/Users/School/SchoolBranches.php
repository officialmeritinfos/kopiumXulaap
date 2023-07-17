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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SchoolBranches extends BaseController
{
    use PubFunctions;
    //landing page
    public function landingPage($slug)
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->firstOrFail();


        return view('dashboard.users.pages.schools.branches.branches')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Manage Schools',
            'user'=>$user,
            'school'=>$school,
            'branches'=>UserSchoolBranch::where('school',$school->id)->paginate(15)
        ]);
    }
    //add branch
    public function addBranch(Request $request)
    {
        try {
            $web = GeneralSetting::find(1);
            $user = Auth::user();
            $validator = Validator::make($request->all(),[
                'name'=>['required','string','max:150'],
                'school'=>[
                    'required',
                    'string',
                    Rule::exists('user_schools','reference')->where('user',$user->id)
                ],
            ])->stopOnFirstFailure();

            if ($validator->fails()) {
                return $this->sendError('validation.error', ['error' => $validator->errors()->all()]);
            }

            $input = $validator->validated();

            $school = UserSchool::where(['reference'=>$input['school'],'user'=>$user->id])->first();

            //let's check that branch is unique
            $branchExists = UserSchoolBranch::where([
                'school'=>$school->id,'name'=>$input['name']
            ])->first();

            if (!empty($branchExists)){
                return $this->sendError('branch.error', [
                    'error' => 'Campus '.$input['name'].' already exists in Institution.'
                ]);
            }

            $reference = $this->generateUniqueReference('user_school_branches','reference',7);
            $branch = UserSchoolBranch::create([
                'user'=>$user->id,'reference'=>$reference,
                'name'=>$input['name'],'school'=>$school->id
            ]);
            if (!empty($branch)){
                return $this->sendResponse([
                    'redirectTo'=>url()->previous()
                ],'Institution Branch successfully added.');
            }
            return $this->sendError('school.error',['error'=>'Unable to add branch']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while adding school branch: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
    //edit branch
    public function editBranch(Request $request)
    {
        try {
            $web = GeneralSetting::find(1);
            $user = Auth::user();
            $validator = Validator::make($request->all(),[
                'name'=>['required','string','max:150'],
                'school'=>[
                    'required',
                    'string',
                    Rule::exists('user_schools','reference')->where('user',$user->id)
                ],
                'branch'=>[
                    'required',
                    'numeric',
                    Rule::exists('user_school_branches','id')->where('user',$user->id)
                ],
            ])->stopOnFirstFailure();

            if ($validator->fails()) {
                return $this->sendError('validation.error', ['error' => $validator->errors()->all()]);
            }

            $input = $validator->validated();

            $school = UserSchool::where(['reference'=>$input['school'],'user'=>$user->id])->first();

            //let's check that branch is unique
            $branchExists = UserSchoolBranch::where([
                'school'=>$school->id,'name'=>$input['name']
            ])->where('id','!=',$input['branch'])->first();

            if (!empty($branchExists)){
                return $this->sendError('branch.error', [
                    'error' => $input['name'].' already exists in Institution.'
                ]);
            }

            //check if branch belongs to school
            $branchToSchool = UserSchoolBranch::where([
                'school'=>$school->id,'id'=>$input['branch']
            ])->first();

            if (empty($branchToSchool)){
                return $this->sendError('branch.error', [
                    'error' => 'Branch does not belong to institution.'
                ]);
            }
            if (UserSchoolBranch::where('id',$branchToSchool->id)->update([
                'name'=>$input['name']
            ])){
                return $this->sendResponse([
                    'redirectTo'=>url()->previous()
                ],'Institution Branch successfully updated.');
            }
            return $this->sendError('school.error',['error'=>'Unable to update branch']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while adding school branch: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
    //edit status
    public function editStatus(Request $request, $ref)
    {
        $user = Auth::user();

        $type = $request->get('type');

        $branch = UserSchoolBranch::where(['user'=>$user->id,'reference'=>$ref])->first();
        if (empty($branch)){
            return back()->with('error','Branch not found');
        }

        //check the type
        if ($type=='activate'){
            $branch->status = 1;
            $branch->save();
            return back()->with('success','Activated');
        }else{
            $branch->status = 3;
            $branch->save();
            return back()->with('success','Deactivated');
        }
    }
    //branch removal
    public function deleteBranch($slug,$ref)
    {
        $user = Auth::user();
        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->first();
        if (empty($school)){
            return back()->with('error','Invalid selection');
        }

        $branch = UserSchoolBranch::where([
            'user'=>$user->id,'school'=>$school->id,
            'reference'=>$ref
        ])->first();
        if (empty($branch)){
            return back()->with('error','Branch not found.');
        }

        $branch->delete();

        return back()->with('success','Branch deleted');
    }
}
