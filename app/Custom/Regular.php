<?php

namespace App\Custom;

use App\Models\Country;
use App\Models\User;
use App\Models\UserSchoolBranch;
use App\Models\UserSchoolClass;

class Regular
{
    //get user from Id
    public function getUserById($id)
    {
        return User::where('id',$id)->first();
    }
    //fetch country by code
    public function getCountryByCode($code)
    {
        return Country::where('iso3',$code)->first();
    }
    //fetch branch from ID
    public function getBranchFromId($id)
    {
        return UserSchoolBranch::where('id',$id)->first();
    }
    //get classes under a branch
    public function classesUnderBranch($branch)
    {
        return UserSchoolClass::where('branch',$branch)->get();
    }
    //get class by Id
    public function getClassById($id)
    {
        return UserSchoolClass::where('id',$id)->first();
    }
}
