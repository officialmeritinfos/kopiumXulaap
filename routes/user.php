<?php

use App\Http\Controllers\Dashboard\Users\Home;
use App\Http\Controllers\Dashboard\Users\School\AcademicSessions;
use App\Http\Controllers\Dashboard\Users\School\AcademicTerms;
use App\Http\Controllers\Dashboard\Users\School\SchoolBranches;
use App\Http\Controllers\Dashboard\Users\School\SchoolClasses;
use App\Http\Controllers\Dashboard\Users\School\Schools;
use App\Http\Controllers\Dashboard\Users\School\SchoolSettings;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| USER Routes
|--------------------------------------------------------------------------
|
| Here is where you can register auth routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" and "auth"  middleware group. Now create something great!
|
*/

/* ====================== DASHBOARD CONTROLLER REQUEST ===============================*/
Route::get('dashboard',[Home::class,'landingPage'])
    ->name('dashboard');
Route::get('dashboard/profile-setup',[Home::class,'setupProfile'])
    ->name('setupProfile');

Route::post('dashboard/setup-authenticator',[Home::class,'verifyAuthenticatorSetup'])
    ->name('dashboard.set2Fa');//SETUP 2FA
Route::post('dashboard/profile-setup/update-profile',[Home::class,'updateProfile'])
    ->name('setupProfile.submitProfile');//UPDATE PROFILE
Route::post('dashboard/profile-setup/update-picture',[Home::class,'updateProfilePic'])
    ->name('setupProfile.updatePic');//UPDATE PROFILE
/* ====================== SCHOOL CONTROLLER REQUEST ===============================*/
Route::get('schools/index',[Schools::class,'landingPage'])
    ->name('school.index');
Route::post('schools/create/process',[Schools::class,'processAdd'])
    ->name('school.create');
Route::get('schools/{slug}/detail',[Schools::class,'schoolDetail'])
    ->name('school.detail');
/* ====================== SCHOOL BRANCHES CONTROLLER REQUEST ===============================*/
Route::get('schools/{slug}/branches',[SchoolBranches::class,'landingPage'])
    ->name('school.branches');
Route::get('schools/branches/{ref}/status/edit',[SchoolBranches::class,'editStatus'])
    ->name('school.branch.status.edit');
Route::get('schools/{slug}/branches/{ref}/delete',[SchoolBranches::class,'deleteBranch'])
    ->name('school.branch.delete');
//POST REQUESTS
Route::post('schools/branches/add',[SchoolBranches::class,'addBranch'])
    ->name('school.branches.add');
Route::post('schools/branches/edit',[SchoolBranches::class,'editBranch'])
    ->name('school.branches.edit');
/* ====================== SCHOOL SETTING CONTROLLER REQUEST ===============================*/
Route::get('schools/{slug}/setups',[SchoolSettings::class,'landingPage'])
    ->name('school.settings');
//SCHOOL ACADEMIC SESSIONS
Route::get('schools/{slug}/setups/sessions',[AcademicSessions::class,'landingPage'])
    ->name('school.sessions');
Route::post('schools/sessions/add',[AcademicSessions::class,'addSession'])
    ->name('school.sessions.add');
Route::get('schools/{slug}/setups/sessions/{branch}/',[AcademicSessions::class,'detail'])
    ->name('school.sessions.details');
Route::get('schools/sessions/{ref}/status/edit',[AcademicSessions::class,'editStatus'])
    ->name('school.session.status.edit');
Route::get('schools/{slug}/sessions/{ref}/delete',[AcademicSessions::class,'delete'])
    ->name('school.session.delete');
Route::post('schools/session/edit',[AcademicSessions::class,'editSession'])
    ->name('school.session.edit');
//SCHOOL ACADEMIC SEMESTER
Route::get('schools/{slug}/setups/terms',[AcademicTerms::class,'landingPage'])
    ->name('school.terms');
Route::post('schools/terms/add',[AcademicTerms::class,'addTerm'])
    ->name('school.terms.add');
Route::get('schools/{slug}/setups/terms/{branch}/',[AcademicTerms::class,'detail'])
    ->name('school.terms.details');
Route::get('schools/terms/{ref}/status/edit',[AcademicTerms::class,'editStatus'])
    ->name('school.term.status.edit');
Route::get('schools/{slug}/terms/{ref}/delete',[AcademicTerms::class,'delete'])
    ->name('school.term.delete');
Route::post('schools/terms/edit',[AcademicTerms::class,'editTerm'])
    ->name('school.term.edit');
//SCHOOL ACADEMIC CLASSES
Route::get('schools/{slug}/setups/classes',[SchoolClasses::class,'landingPage'])
    ->name('school.classes');
Route::post('schools/classes/add',[SchoolClasses::class,'addClass'])
    ->name('school.classes.add');
Route::post('schools/classes/single/add',[SchoolClasses::class,'addClassSingle'])
    ->name('school.classes.add.single');
Route::get('schools/{slug}/setups/classes/{branch}/',[SchoolClasses::class,'detail'])
    ->name('school.classes.details');
Route::get('schools/classes/{ref}/status/edit',[SchoolClasses::class,'editStatus'])
    ->name('school.class.status.edit');
Route::get('schools/{slug}/classes/{ref}/delete',[SchoolClasses::class,'delete'])
    ->name('school.class.delete');
Route::get('schools/{slug}/branch/{branch}/classes/{ref}/edit',[SchoolClasses::class,'editClass'])
    ->name('school.class.edit');
Route::post('schools/classes/edit/process',[SchoolClasses::class,'editClassSingle'])
    ->name('school.class.edit.process');

Route::get('logout',[Home::class,'logout'])->name('logout');
