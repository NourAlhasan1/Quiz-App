<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\QuiezQuestion;
use App\Models\StudentQuiz;
use Illuminate\Http\Request;
use Validator;

class QuiezQuestionController extends BaseController
{
    public function createExam(Request  $request){
        $input=$request->all();
        $validator=Validator::make($input,[
            'quize_id'=>'required|required|integer|exists:quizes,id',
            'questions'=>'required|array',
            'questions.*.question' => 'required|integer|exists:questions,id',
            'students'=>'required|array',
            'students.*.student' => 'required|integer|exists:students,uni_num',

        ]);

        if($validator->fails())
        {
            return $this->sendError('Please validate error' , $validator->errors());
        }
        $data = $validator->validated();
        $qdata = $data['questions'];
        $sdata = $data['students'];
      
        foreach ($sdata as $item) {
            $ss = new StudentQuiz();
            $ss->quiz_id = $data['quize_id'];
            $ss->uni_num = $item['student'];
            $ss->save();
        }
        return $this->sendResponse($qdata,'Creating exam completed');
    }
}
