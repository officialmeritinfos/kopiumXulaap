<?php

namespace App\Http\Controllers\Dashboard\Users\School;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\UserSchool;
use App\Models\UserSchoolBranch;
use App\Models\UserSchoolSemester;
use App\Traits\PubFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AcademicTerms extends BaseController
{
    use PubFunctions;
    //landing Page
    public function landingPage($slug)
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->firstOrFail();

        $terms = UserSchoolSemester::where('school',$school->id)->groupBy('branch')->paginate(15);

        return view('dashboard.users.pages.schools.settings.terms.index')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Manage '.$school->name.' Academic Terms',
            'user'=>$user,
            'school'=>$school,
            'terms'=>$terms,
            'branches'=>UserSchoolBranch::where('school',$school->id)->get(),
        ]);
    }
    //term detail
    public function detail($slug,$branchId)
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->firstOrFail();

        $branch = UserSchoolBranch::where(['school'=>$school->id,'id'=>$branchId])->firstOrFail();

        return view('dashboard.users.pages.schools.settings.terms.detail')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Manage '.$branch->name.' Academic Terms ',
            'user'=>$user,
            'school'=>$school,
            'branch'=>$branch,
            'terms'=>UserSchoolSemester::where(['branch'=>$branch->id])->paginate(15)
        ]);
    }
    //add term
    public function addTerm(Request $request)
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
                //let's check that branch exists
                $branchExists = UserSchoolBranch::where([
                    'school'=>$school->id,'id'=>$input['branch'][$i]
                ])->first();

                if (empty($branchExists)){
                    return $this->sendError('branch.error', ['error' => 'Branch not found']);
                }

                $reference = $this->generateUniqueReference('user_school_semesters','reference',7);

                //check if term has been added
                $termExists = UserSchoolSemester::where([
                    'branch'=>$branchExists->id,'name'=>$input['name']
                ])->first();

                if (!empty($termExists)){
                    return $this->sendError('term.error', ['error' => 'Semester already added.']);
                }

                $term = UserSchoolSemester::create([
                    'user'=>$user->id,'reference'=>$reference,
                    'name'=>$input['name'],'school'=>$school->id,
                    'branch'=>$input['branch'][$i]
                ]);
            }

            if (!empty($term)){
                return $this->sendResponse([
                    'redirectTo'=>url()->previous()
                ],'Academic term successfully added.');
            }
            return $this->sendError('school.error',['error'=>'Unable to add academic term']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while adding academic term: ' . $exception->getMessage());
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

        $term = UserSchoolSemester::where(['user'=>$user->id,'reference'=>$ref])->first();
        if (empty($term)){
            return back()->with('error','term not found');
        }

        //check the type
        if ($type=='activate'){
            $term->status = 1;
            $term->save();
            return back()->with('success','Activated');
        }else{
            $term->status = 3;
            $term->save();
            return back()->with('success','Deactivated');
        }
    }
    //term removal
    public function delete($slug,$ref)
    {
        $user = Auth::user();
        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->first();
        if (empty($school)){
            return back()->with('error','Invalid selection');
        }

        $term = UserSchoolSemester::where([
            'user'=>$user->id,'school'=>$school->id,
            'reference'=>$ref
        ])->first();
        if (empty($term)){
            return back()->with('error','term not found.');
        }

        $term->delete();

        return back()->with('success','Term deleted');
    }
    //edit term
    public function editTerm(Request $request)
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
                    Rule::exists('user_school_semesters','id')->where('user',$user->id)
                ],
            ])->stopOnFirstFailure();

            if ($validator->fails()) {
                return $this->sendError('validation.error', ['error' => $validator->errors()->all()]);
            }

            $input = $validator->validated();

            $school = UserSchool::where(['reference'=>$input['school'],'user'=>$user->id])->first();

            //let's check that term is unique
            $termExist = UserSchoolSemester::where([
                'school'=>$school->id,'name'=>$input['name'],
                'branch'=>$input['branch']
            ])->where('id','!=',$input['id'])->first();

            if (!empty($termExist)){
                return $this->sendError('term.error', [
                    'error' => $input['name'].' already exists.'
                ]);
            }

            //check if term belongs to school & branch
            $belongToSchool = UserSchoolSemester::where([
                'school'=>$school->id,'branch'=>$input['branch'],
                'id'=>$input['id']
            ])->first();

            if (empty($belongToSchool)){
                return $this->sendError('branch.error', [
                    'error' => 'Academic Term not found.'
                ]);
            }
            if (UserSchoolSemester::where('id',$belongToSchool->id)->update([
                'name'=>$input['name']
            ])){
                return $this->sendResponse([
                    'redirectTo'=>url()->previous()
                ],'Academic Term successfully updated.');
            }
            return $this->sendError('term.error',['error'=>'Unable to update academic term']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while adding school academic term: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
}
