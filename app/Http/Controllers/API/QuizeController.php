<?php

namespace App\Http\Controllers\API;
use DB;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Quize;
use App\Models\Question;
use App\Models\Choice;
use App\Models\studentQuiz;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class QuizeController extends BaseController
{
    public function createQuiz(Request $request)
    {
        $input=$request->all();
        $validator=Validator::make($input,[
            'title'=>'required',
            'duration'=>'required',
            'mark'=>'required',
            'date'=>'required',
            'user_id'=>'required|integer|exists:users,id',
            'course_id'=>'required|integer|exists:courses,id'


        ]);

            if($validator->fails())
            {
                return $this->sendError('Please validate error' , $validator->errors());
            }
            $quiz = Quize::create($input);
            return $this->sendResponse($quiz,'Creating course completed');
    }
    public function addQuestion(Request $request)
    {
        $input=$request->all();
        $validator=Validator::make($input,[
            'quize_id' => 'required|integer|exists:quizes,id',
            'mark' => 'required|integer',
            'text' => 'required|string',
            'choices' => 'required|array',
            'choices.*.choice' => 'required|string',
            'choices.*.is_true' => 'required|boolean',
        ]);
        if($validator->fails())
        {
            return $this->sendError('Please validate error' , $validator->errors());
        }
        $questionData = $validator->validated();
        $question = Question::create($questionData);
        $choicesData = $questionData['choices'];
        foreach ($choicesData as $choiceData) {
            $choice = new Choice();
            $choice->question_id = $question->id;
            $choice->choice = $choiceData['choice'];
            $choice->is_true = $choiceData['is_true'];
            $choice->save();
        }
        return $this->sendResponse($questionData,'Creating course completed');
    }
    public function getAllQuizzes()
    {
        $user=Auth::user();
        $quizzes = $user->quizes;

        return $this->sendResponse($quizzes,'All quizzes');
    }
    public function quizinfo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|exists:quizes,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error' , $validator->errors());
        }

        $quizId = $request->input('quiz_id');

        $quiz = Quize::with('questions.choices')->find($quizId);
        if (!$quiz) {
            return $this->sendError('Invaled quiz ID',[] , 404);
        }

        return $this->sendResponse($quiz,'Quiz info');


    }
    public function updateQuiz(Request $request, $quizId)
    {
        $validator = Validator::make($request->all(), [

            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.mark' => 'required|integer',
            'questions.*.text' => 'required|string',
            'questions.*.choices' => 'required|array',
            'questions.*.choices.*.id' => 'required|exists:choices,id',
            'questions.*.choices.*.choice' => 'required|string',
            'questions.*.choices.*.is_true' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error' , $validator->errors());
        }



        $questions = $request->input('questions');

        foreach ($questions as $questionData) {
            $question = Question::find($questionData['id']);
            if (!$question || $question->quize_id != $quizId) {
                return $this->sendError('Question not found or does not belong to the specified quiz',$question , 404);
            }

            $question->mark = $questionData['mark'];
            $question->text = $questionData['text'];
            $question->save();

            $choices = $questionData['choices'];

            foreach ($choices as $choiceData) {
                $choice = Choice::find($choiceData['id']);
                if (!$choice || $choice->question_id != $question->id) {
                    return $this->sendError('Choice not found or does not belong to the specified question',$choice , 404);
                }

                $choice->choice = $choiceData['choice'];
                $choice->is_true = $choiceData['is_true'];
                $choice->save();
            }
        }

        return $this->sendResponse($request->all(),'Quiz updated successfully');
    }
    public function deleteQuestion($id)
    {
        $question = Question::find($id);
        if(!$question)
        {
            return $this->sendError('Question not found',[] , 404);
        }
        // Delete the question and its choices
        $question->choices()->delete();
        $question->delete();
        return $this->sendResponse($question,'Question and choices deleted successfully');
    }
    public function deleteQuiz($id)
    {
        $quiz=Quize::find($id);
        if(!$quiz)
        {
            return $this->sendError('Quiz not found',[] , 404);
        }
        $deletedChoices = [];
        $deletedQuestions = [];
        StudentQuiz::where('quiz_id', $id)->delete();

        // Delete choices associated with each question
        foreach ($quiz->questions as $question) {
            $deletedChoices += $question->choices()->get()->toArray();
            $question->choices()->delete();
        }
        $deletedQuestions = $quiz->questions()->get()->toArray();
        // Delete questions
        $quiz->questions()->delete();
        $quiz->delete();
        $response = [
            'message' => 'Quiz deleted successfully',
            'deletedQuiz' => $quiz,
            'deletedQuestions' => $deletedQuestions,
            'deletedChoices' => $deletedChoices,
        ];
        // Finally, delete the quiz itself

        return $this->sendResponse($response,'Quiz deleted successfully');
    }
}
