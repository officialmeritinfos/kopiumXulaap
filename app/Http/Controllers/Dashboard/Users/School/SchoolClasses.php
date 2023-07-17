<?php

namespace App\Http\Controllers\Dashboard\Users\School;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\UserSchool;
use App\Models\UserSchoolBranch;
use App\Models\UserSchoolClass;
use App\Traits\PubFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SchoolClasses extends BaseController
{
    use PubFunctions;
    //landing Page
    public function landingPage($slug)
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->firstOrFail();

        $classes = UserSchoolClass::where('school',$school->id)->groupBy('branch')->paginate(15);

        return view('dashboard.users.pages.schools.settings.classes.index')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Manage '.$school->name.' Academic classes',
            'user'=>$user,
            'school'=>$school,
            'classes'=>$classes,
            'branches'=>UserSchoolBranch::where('school',$school->id)->get(),
        ]);
    }
    //class detail
    public function detail($slug,$branchId)
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->firstOrFail();

        $branch = UserSchoolBranch::where(['school'=>$school->id,'id'=>$branchId])->firstOrFail();

        return view('dashboard.users.pages.schools.settings.classes.detail')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Manage '.$branch->name.' Academic classes ',
            'user'=>$user,
            'school'=>$school,
            'branch'=>$branch,
            'classes'=>UserSchoolClass::where(['branch'=>$branch->id])->paginate(15),
            'branchClasses'=>UserSchoolClass::where(['branch'=>$branch->id])->get(),
            'branches'=>UserSchoolBranch::where('school',$school->id)->get(),
        ]);
    }
    //add class
    public function addClass(Request $request)
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
                'initialClass'=>['nullable','numeric'],
                'finalClass'=>['nullable','numeric'],
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

                $reference = $this->generateUniqueReference('user_school_classes','reference',7);

                //let's check if there is a class already marked as first
                if ($request->has('initialClass')){
                    $classWithInitial = UserSchoolClass::where([
                        'branch'=>$branchExists->id,'isFirst'=>1
                    ])->first();
                    if (!empty($classWithInitial)){
                        return $this->sendError('class.error', [
                            'error' => 'A class marked as the initial class already exists in '.$branchExists->name
                        ]);
                    }
                }

                //let's check if there is a class already marked as final
                if ($request->has('finalClass')){
                    $classWithFinal = UserSchoolClass::where([
                        'branch'=>$branchExists->id,'isLast'=>1
                    ])->first();
                    if (!empty($classWithFinal)){
                        return $this->sendError('class.error', [
                            'error' => 'A class marked as the final class already exists in '.$branchExists->name
                        ]);
                    }
                }
                if ($request->has('finalClass') && $request->has('initialClass')){
                    return $this->sendError('class.error', [
                        'error' => 'Class cannot be marked as both initial and last.'
                    ]);
                }

                //check if class has been added
                $classExists = UserSchoolClass::where([
                    'branch'=>$branchExists->id,'name'=>$input['name']
                ])->first();

                if (!empty($classExists)){
                    return $this->sendError('class.error', ['error' => 'Class already added to '.$branchExists->name]);
                }

                $class = UserSchoolClass::create([
                    'user'=>$user->id,'reference'=>$reference,
                    'name'=>$input['name'],'school'=>$school->id,
                    'branch'=>$input['branch'][$i],
                    'isFirst'=>$request->has('initialClass')?1:2,
                    'isLast'=>$request->has('finalClass')?1:2
                ]);
            }

            if (!empty($class)){
                return $this->sendResponse([
                    'redirectTo'=>url()->previous()
                ],'Academic class successfully added.');
            }
            return $this->sendError('school.error',['error'=>'Unable to add academic class']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while adding academic class: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
    //add class
    public function addClassSingle(Request $request)
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
                'precedeClass'=>[
                    'nullable',
                    'numeric',
                    Rule::exists('user_school_classes','id')->where('user',$user->id)
                ],
                'proceedClass'=>[
                    'nullable',
                    'numeric',
                    Rule::exists('user_school_classes','id')->where('user',$user->id)
                ],
                'initialClass'=>['nullable','numeric'],
                'finalClass'=>['nullable','numeric'],
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

                //let's check if the class before and after are the same
                if ($input['precedeClass']==$input['proceedClass']){
                    return $this->sendError('class.error', [
                        'error' => 'Preceding and Proceeding class cannot be the same'
                    ]);
                }
                //check if preceding exists
                if (!empty($input['precedeClass'])){
                    $precedingExists = UserSchoolClass::where([
                        'branch'=>$branchExists->id,'id'=>$input['precedeClass']
                    ])->first();

                    if (empty($precedingExists)){
                        return $this->sendError('class.error', [
                            'error' => 'Preceding class cannot be found.'
                        ]);
                    }
                }
                if (!empty($input['proceedClass'])){
                    //proceeding class exists
                    $proceedingExists = UserSchoolClass::where([
                        'branch'=>$branchExists->id,'id'=>$input['proceedClass']
                    ])->first();

                    if (empty($proceedingExists)){
                        return $this->sendError('class.error', [
                            'error' => 'Proceeding class cannot be found.'
                        ]);
                    }
                }

                $reference = $this->generateUniqueReference('user_school_classes','reference',7);

                //let's check if there is a class already marked as first
                if ($request->has('initialClass')){
                    $classWithInitial = UserSchoolClass::where([
                        'branch'=>$branchExists->id,'isFirst'=>1
                    ])->first();
                    if (!empty($classWithInitial)){
                        return $this->sendError('class.error', [
                            'error' => 'A class marked as the initial class already exists in '.$branchExists->name
                        ]);
                    }
                }

                //let's check if there is a class already marked as final
                if ($request->has('finalClass')){
                    $classWithFinal = UserSchoolClass::where([
                        'branch'=>$branchExists->id,'isLast'=>1
                    ])->first();
                    if (!empty($classWithFinal)){
                        return $this->sendError('class.error', [
                            'error' => 'A class marked as the final class already exists in '.$branchExists->name
                        ]);
                    }
                }
                if ($request->has('finalClass') && $request->has('initialClass')){
                    return $this->sendError('class.error', [
                        'error' => 'Class cannot be marked as both initial and last.'
                    ]);
                }

                //check if class has been added
                $classExists = UserSchoolClass::where([
                    'branch'=>$branchExists->id,'name'=>$input['name']
                ])->first();

                if (!empty($classExists)){
                    return $this->sendError('class.error', ['error' => 'Class already added to '.$branchExists->name]);
                }

                $class = UserSchoolClass::create([
                    'user'=>$user->id,'reference'=>$reference,
                    'name'=>$input['name'],'school'=>$school->id,
                    'branch'=>$input['branch'][$i],
                    'isFirst'=>$request->has('initialClass')?1:2,
                    'isLast'=>$request->has('finalClass')?1:2,
                    'classAfter'=>$proceedingExists->id,
                    'classBefore'=>$precedingExists->id,
                ]);
            }

            if (!empty($class)){
                return $this->sendResponse([
                    'redirectTo'=>url()->previous()
                ],'Academic class successfully added.');
            }
            return $this->sendError('school.error',['error'=>'Unable to add academic class']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while adding academic class: ' . $exception->getMessage());
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

        $class = UserSchoolClass::where(['user'=>$user->id,'reference'=>$ref])->first();
        if (empty($class)){
            return back()->with('error','Class not found');
        }

        //check the type
        if ($type=='activate'){
            $class->status = 1;
            $class->save();
            return back()->with('success','Activated');
        }else{
            $class->status = 3;
            $class->save();
            return back()->with('success','Deactivated');
        }
    }
    //class removal
    public function delete($slug,$ref)
    {
        $user = Auth::user();
        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->first();
        if (empty($school)){
            return back()->with('error','Invalid selection');
        }

        $class = UserSchoolClass::where([
            'user'=>$user->id,'school'=>$school->id,
            'reference'=>$ref
        ])->first();
        if (empty($class)){
            return back()->with('error','class not found.');
        }

        $class->delete();

        return back()->with('success','Class deleted');
    }
    //edit class
    public function editClass($slug,$branchId,$ref)
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $school = UserSchool::where(['user'=>$user->id,'slug'=>$slug])->firstOrFail();

        $branch = UserSchoolBranch::where(['school'=>$school->id,'id'=>$branchId])->firstOrFail();
        $class = UserSchoolClass::where([
            'school'=>$school->id,'branch'=>$branchId,'reference'=>$ref
        ])->firstOrFail();

        return view('dashboard.users.pages.schools.settings.classes.edit')->with([
            'web'=>$web,
            'siteName'=>$web->name,
            'pageName'=>'Edit '.$branch->name.' Academic class - '.$class->name,
            'user'=>$user,
            'school'=>$school,
            'branch'=>$branch,
            'branchClasses'=>UserSchoolClass::where(['branch'=>$branch->id])->get(),
            'class'=>$class
        ]);
    }
    //process class edit
    public function editClassSingle(Request $request)
    {
        try {
            $user = Auth::user();
            $validator = Validator::make($request->all(),[
                'name'=>['required','string','max:150'],
                'branch'=>[
                    'required',
                    'numeric',
                    Rule::exists('user_school_branches','id')->where('user',$user->id),
                ],
                'school'=>[
                    'required',
                    'string',
                    Rule::exists('user_schools','reference')->where('user',$user->id)
                ],
                'precedeClass'=>[
                    'nullable',
                    'numeric',
                    Rule::exists('user_school_classes','id')->where('user',$user->id)
                ],
                'proceedClass'=>[
                    'nullable',
                    'numeric',
                    Rule::exists('user_school_classes','id')->where('user',$user->id)
                ],
                'initialClass'=>['nullable','numeric'],
                'finalClass'=>['nullable','numeric'],
                'id'=>[
                    'required',
                    'numeric',
                    Rule::exists('user_school_classes','id')->where('user',$user->id)
                ],
            ])->stopOnFirstFailure();

            if ($validator->fails()) {
                return $this->sendError('validation.error', ['error' => $validator->errors()->all()]);
            }

            $input = $validator->validated();

            $school = UserSchool::where(['reference'=>$input['school'],'user'=>$user->id])->first();

            //let's check that term is unique
            $classExist = UserSchoolClass::where([
                'school'=>$school->id,'name'=>$input['name'],
                'branch'=>$input['branch']
            ])->where('id','!=',$input['id'])->first();

            if (!empty($classExist)){
                return $this->sendError('class.error', [
                    'error' => $input['name'].' already exists.'
                ]);
            }

            //check if class belongs to school & branch
            $belongToSchool = UserSchoolClass::where([
                'school'=>$school->id,'branch'=>$input['branch'],
                'id'=>$input['id']
            ])->first();

            if (empty($belongToSchool)){
                return $this->sendError('class.error', [
                    'error' => 'Academic Class not found.'
                ]);
            }
            //let's check that branch exists
            $branchExists = UserSchoolBranch::where([
                'school'=>$school->id,'id'=>$input['branch']
            ])->first();

            if (empty($branchExists)){
                return $this->sendError('branch.error', ['error' => 'Branch not found']);
            }

            //let's check if the class before and after are the same
            if ($input['precedeClass']==$input['proceedClass']){
                return $this->sendError('class.error', [
                    'error' => 'Preceding and Proceeding class cannot be the same'
                ]);
            }
            //check if preceding exists
            if (!empty($input['precedeClass'])){
                $precedingExists = UserSchoolClass::where([
                    'branch'=>$branchExists->id,'id'=>$input['precedeClass']
                ])->first();

                if (empty($precedingExists)){
                    return $this->sendError('class.error', [
                        'error' => 'Preceding class cannot be found.'
                    ]);
                }
            }
            if (!empty($input['proceedClass'])){
                //proceeding class exists
                $proceedingExists = UserSchoolClass::where([
                    'branch'=>$branchExists->id,'id'=>$input['proceedClass']
                ])->first();

                if (empty($proceedingExists)){
                    return $this->sendError('class.error', [
                        'error' => 'Proceeding class cannot be found.'
                    ]);
                }
            }

            //let's check if there is a class already marked as first
            if ($request->has('initialClass')){
                $classWithInitial = UserSchoolClass::where([
                    'branch'=>$branchExists->id,'isFirst'=>1
                ])->first();
                if (!empty($classWithInitial)){
                    return $this->sendError('class.error', [
                        'error' => 'A class marked as the initial class already exists in '.$branchExists->name
                    ]);
                }
            }

            //let's check if there is a class already marked as final
            if ($request->has('finalClass')){
                $classWithFinal = UserSchoolClass::where([
                    'branch'=>$branchExists->id,'isLast'=>1
                ])->first();
                if (!empty($classWithFinal)){
                    return $this->sendError('class.error', [
                        'error' => 'A class marked as the final class already exists in '.$branchExists->name
                    ]);
                }
            }
            if ($request->has('finalClass') && $request->has('initialClass')){
                return $this->sendError('class.error', [
                    'error' => 'Class cannot be marked as both initial and last.'
                ]);
            }

            $update = UserSchoolClass::where('id',$input['id'])->update([

            ]);

            if (!$update){
                return $this->sendResponse([
                    'redirectTo'=>url()->previous()
                ],'Academic class successfully updated.');
            }
            return $this->sendError('school.error',['error'=>'Unable to update academic class']);
        }catch (\Exception $exception){
            Log::info('Error in  ' . __METHOD__ . ' while updating academic class: ' . $exception->getMessage());
            return $this->sendError('server.error',[
                'error'=>'A server error occurred while processing your request.'
            ]);
        }
    }
}
