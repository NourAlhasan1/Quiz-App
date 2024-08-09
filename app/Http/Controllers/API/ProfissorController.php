<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Profissor;
use App\Models\Quize;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
class ProfissorController extends BaseController
{
    public function addProfessor(Request $request){
        $validator=Validator::make($request->all(),[
            'name' =>'required',
            'phone' =>'required',
            'national_num' =>'required',
            'user_id'=>'required|integer|exists:users,id',
        ]);

        if($validator->fails())
        {
            return $this->sendError('Please validate error' , $validator->errors());
        }
        $input=$request->all();
        

        $profissor = Profissor::create($input);
        $success['name']=$profissor->name;
        $success['phone']=$profissor->phone;
        $success['national_num']=$profissor->national_num;
        $success['user_id'] = $profissor->id;



        return $this->sendResponse($success , 'profissor added successfuly');

    }

    public  function  activateQuiz(Request  $request){
        $validator=Validator::make($request->all(),[
            'id' =>'required|integer|exists:quizes,id',
            'is_active'=>'required'
        ]);

        if($validator->fails())
        {
            return $this->sendError('Please validate error' , $validator->errors());
        }
        $input=$request->all();
        $quiz = Quize::where('id',$input['id'])->first();
        $quiz->is_active = $input['is_active'];
        $quiz->save();
        return $this->sendResponse([] , 'quiz is activate');

    }
}
