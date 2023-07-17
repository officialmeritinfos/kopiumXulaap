<?php

namespace App\Http\Controllers\Dashboard\Users\School;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\UserSchool;
use App\Models\UserSchoolBranch;
use App\Models\UserSchoolSession;
use App\Traits\PubFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AcademicSessions extends BaseController
{
    use PubFunctions;
    //landing Page
    public function landingPage($slug)
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->firstOrFail();

        $sessions = UserSchoolSession::where('school',$school->id)->groupBy('branch')->paginate(15);


        return view('dashboard.users.pages.schools.settings.sessions.index')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Manage '.$school->name.' Academic Sessions',
            'user'=>$user,
            'school'=>$school,
            'sessions'=>$sessions,
            'branches'=>UserSchoolBranch::where('school',$school->id)->get(),
        ]);
    }
    //session detail
    public function detail($slug,$branchId)
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->firstOrFail();

        $branch = UserSchoolBranch::where(['school'=>$school->id,'id'=>$branchId])->firstOrFail();

        return view('dashboard.users.pages.schools.settings.sessions.detail')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Manage '.$branch->name.' Academic Sessions ',
            'user'=>$user,
            'school'=>$school,
            'branch'=>$branch,
            'sessions'=>UserSchoolSession::where(['branch'=>$branch->id])->paginate(15)
        ]);
    }
    //add session
    public function addSession(Request $request)
    {
        try {
            $user = Auth::user();
            $validator = Validator::make($request->all(),[
                'name'=>['required','string','max:150'],
                'branch'=>['required'],
                'branch.*'=>[
                    'required',
                    'numeric',
                    Rule::exists('user_school_branches','id')->where('user',$user->id),
                ],
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

            for ($i=0;$i<count($input['branch']); $i++){
                //let's check that branch is unique
                $branchExists = UserSchoolBranch::where([
                    'school'=>$school->id,'id'=>$input['branch'][$i]
                ])->first();

                if (empty($branchExists)){
                    return $this->sendError('branch.error', ['error' => 'Branch not found']);
                }

                $reference = $this->generateUniqueReference('user_school_sessions','reference',7);

                //check if session has been added
                $sessionExists = UserSchoolSession::where([
                    'branch'=>$branchExists->id,'name'=>$input['name']
                ])->first();

                if (!empty($sessionExists)){
                    return $this->sendError('session.error', ['error' => 'Session already added.']);
                }

                $session = UserSchoolSession::create([
                    'user'=>$user->id,'reference'=>$reference,
                    'name'=>$input['name'],'school'=>$school->id,
                    'branch'=>$input['branch'][$i]
                ]);
            }

            if (!empty($session)){
                return $this->sendResponse([
                    'redirectTo'=>url()->previous()
                ],'Academic session successfully added.');
            }
            return $this->sendError('school.error',['error'=>'Unable to add academic session']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while adding academic session: ' . $exception->getMessage());
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

        $session = UserSchoolSession::where(['user'=>$user->id,'reference'=>$ref])->first();
        if (empty($session)){
            return back()->with('error','Session not found');
        }

        //check the type
        if ($type=='activate'){
            $session->status = 1;
            $session->save();
            return back()->with('success','Activated');
        }else{
            $session->status = 3;
            $session->save();
            return back()->with('success','Deactivated');
        }
    }
    //session removal
    public function delete($slug,$ref)
    {
        $user = Auth::user();
        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->first();
        if (empty($school)){
            return back()->with('error','Invalid selection');
        }

        $session = UserSchoolSession::where([
            'user'=>$user->id,'school'=>$school->id,
            'reference'=>$ref
        ])->first();
        if (empty($session)){
            return back()->with('error','Session not found.');
        }

        $session->delete();

        return back()->with('success','Session deleted');
    }
    //edit session
    public function editSession(Request $request)
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
                'id'=>[
                    'required',
                    'numeric',
                    Rule::exists('user_school_sessions','id')->where('user',$user->id)
                ],
            ])->stopOnFirstFailure();

            if ($validator->fails()) {
                return $this->sendError('validation.error', ['error' => $validator->errors()->all()]);
            }

            $input = $validator->validated();

            $school = UserSchool::where(['reference'=>$input['school'],'user'=>$user->id])->first();

            //let's check that session is unique
            $sessionExist = UserSchoolSession::where([
                'school'=>$school->id,'name'=>$input['name'],
                'branch'=>$input['branch']
            ])->where('id','!=',$input['id'])->first();

            if (!empty($sessionExist)){
                return $this->sendError('session.error', [
                    'error' => $input['name'].' already exists.'
                ]);
            }

            //check if session belongs to school & branch
            $belongToSchool = UserSchoolSession::where([
                'school'=>$school->id,'branch'=>$input['branch'],
                'id'=>$input['id']
            ])->first();

            if (empty($belongToSchool)){
                return $this->sendError('branch.error', [
                    'error' => 'Academic Session not found.'
                ]);
            }
            if (UserSchoolSession::where('id',$belongToSchool->id)->update([
                'name'=>$input['name']
            ])){
                return $this->sendResponse([
                    'redirectTo'=>url()->previous()
                ],'Academic Session successfully updated.');
            }
            return $this->sendError('session.error',['error'=>'Unable to update academic session']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while adding school academic session: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
}
