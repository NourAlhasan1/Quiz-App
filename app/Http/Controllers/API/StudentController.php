<?php

namespace App\Http\Controllers\API;


use App\Models\QuiezQuestion;
use App\Models\Quize;
use App\Models\student;
use App\Models\mark;
use App\Models\studentAnswer;
use App\Models\Choice;
use App\Models\Question;
use App\Models\StudentQuiz;
use Illuminate\Http\Request;
use Validator;
class StudentController extends BaseController
{
    public function addStudent(Request $request){
        $validator=Validator::make($request->all(),[
            'uni_num'=>'required',
            'f_name' =>'required',
            's_name' =>'required',
            'l_name' =>'required',
            'm_name' =>'required',
            'number' =>'required',
            'national_num' =>'required',
            'user_id'=>'required|integer|exists:users,id',
        ]);

        if($validator->fails())
        {
            return $this->sendError('Please validate error' , $validator->errors());
        }
        $input=$request->all();


        $student = student::create($input);
        $success['uni_num']=$student->uni_num;
        $success['f_name']=$student->f_name;
        $success['l_name']=$student->l_name;
        $success['m_name']=$student->m_name;
        $success['s_name']=$student->s_name;
        $success['number']=$student->number;
        $success['national_num']=$student->national_num;
        $success['user_id'] = $student->user_id;



        return $this->sendResponse($success , 'student added successfuly');

    }
    public function addAnswer(Request $request){
        $validator = Validator::make($request->all(), [
            'uni_num' => 'required|integer|exists:students,uni_num',
            'quiz_id' => 'required|integer|exists:quizes,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.choice_id' => 'required|exists:choices,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $input = $request->all();


        $exist = Mark::where('uni_num', $request->uni_num)->first();
        if($exist)
        {
            return response()->json(['error' => 'already submitted'], 404);
        }
        $correctAnswers = 0;
        $hh=0;
        foreach ($request->answers as $answer) {
            $question = Question::find($answer['question_id']);
            $choice = Choice::find($answer['choice_id']);

            if ($choice->is_true) {
                $correctAnswers+=$question->mark;
            }
            StudentAnswer::create([
                'quiz_id' => $request->quiz_id,
                'question_id' => $answer['question_id'],
                'choice_id' => $answer['choice_id'],
                'uni_num' => $request->uni_num
            ]);
            $hh+=1;

        }




        $mark = new Mark();
        $mark->uni_num = $input['uni_num'];
        $mark->mark = $correctAnswers;
        $mark->quiz_id = $input['quiz_id'];
        $mark->save();

        return $this->sendResponse($correctAnswers, 'choose answer and marks added');
    }
    public function getExam(Request $request){
        $validator=Validator::make($request->all(),[
            'uni_num'=>'required|integer|exists:students,uni_num',
            'quiz_id' =>'required|integer|exists:quizes,id',

        ]);

        if($validator->fails())
        {
            return $this->sendError('Please validate error' , $validator->errors());
        }
        $input=$request->all();
        $in = StudentQuiz::where('uni_num',$input['uni_num'])->where('quiz_id',$input['quiz_id'])->first();
        $quiz = Quize::where('id',$input['quiz_id'])->first();


        if($in&&$quiz->is_active){
            $questions = Quize::where('id',$input['quiz_id'])->with('questions.choices')->get();
            $success['questions'] = $questions;
            return $this->sendResponse($success , '');
        }

        return $this->sendResponse([], 'you do not have Exam');
    }

    public function reviewAnswers(Request $request){
        $validator=Validator::make($request->all(),[
            'uni_num'=>'required|integer|exists:students,uni_num',
            'quiz_id' =>'required|integer|exists:quizes,id',

        ]);

        if($validator->fails())
        {
            return $this->sendError('Please validate error' , $validator->errors());
        }
        $input=$request->all();
        $questions = Quize::where('id',$input['quiz_id'])->with('questions.choices')->get();
        $answers = studentAnswer::where('uni_num',$input['uni_num'])->where('quiz_id',$input['quiz_id'])->with('question')->get();
        $success['questions'] = $questions;
        $success['answers'] = $answers;
        return $this->sendResponse($success , '');


    }
}
