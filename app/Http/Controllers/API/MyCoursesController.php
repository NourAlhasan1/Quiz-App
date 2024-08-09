<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Assigned;
use Illuminate\Support\Facades\Auth;
class MyCoursesController extends BaseController
{
    //
    public function getMycourses()
    {

        $user = Auth::user();
        $course = $user->courses;

        return $this->sendResponse($course, 'All my assigned courses');

}

}
