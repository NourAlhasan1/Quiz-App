<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Quize;
use App\Models\Question;
use App\Models\Choice;
use App\Models\User;
use App\Models\Course;
use App\Models\student;
use App\Models\studentAnswer;
use App\Models\StudentQuiz;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use DB;

class AssigneCourses extends BaseController
{
    public function assignExam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uni_num' => 'required|array',
            'uni_num.*' => 'exists:students,uni_num',
            'quiz_id' => 'required|exists:quizes,id',
        ]);

        if ($validator->fails()) {
            $invalidUniNums = [];

            foreach ($request->input('uni_num') as $key => $uniNum) {
                if ($validator->errors()->has('uni_num.' . $key)) {
                    $errorMessage = $validator->errors()->first('uni_num.' . $key);
                    $invalidUniNums['uni_num.' . $key] = 'The selected ' . $uniNum . ' is invalid. ';
                }
            }
            if($invalidUniNums==[])
            return $this->sendError('error',$validator->errors());
            return $this->sendError('error',$invalidUniNums);
        }

        $universityNumbers = $request->input('uni_num');
        $examId = $request->input('quiz_id');

        // Check if the exam ID exists in the exams table


        $assignedCount = StudentQuiz::where('quiz_id', $examId)->exists();


    if ($assignedCount ) {
        return $this->sendError($examId,['Quiz is already assigned'], 400);}

        // Insert the assigned records into the assigned table
        foreach ($universityNumbers as $uniNum) {
            DB::table('student_quiz')->insert([
                'quiz_id' => $examId,
                'uni_num' => $uniNum,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->sendResponse($request->all(),'Quiz assigned successfully');
    }
}
