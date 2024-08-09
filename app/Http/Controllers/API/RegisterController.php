<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\student;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use DB;


class RegisterController extends BaseController
{
    //
    public function register(Request $request)
    {
        $url = $request->url();
        $type = strpos($url, 'student') !== false ? 1 : 0;

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'name' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = new User($input);
        $user->role = $type; // Set the user type based on the URL condition
        $user->save();

        $success['token'] = $user->createToken('Tasneem')->accessToken;
        $success['user_id'] = $user->id;
        if($type==0)
        $success['courses'] = $user->courses;
        else
        $success['student info'] = $user->student;

        return $this->sendResponse($success, 'User registered successfully');
    }

    public function login(Request $request )
    {
        if(Auth::attempt(['email' =>$request->email, 'password' => $request->password]))
        {
            $user=Auth::user();
            $success['user_id'] = $user->id;
            $success['token']=$user->createToken('Tasneem')->accessToken;
            $success['name']=$user->name;
            if($user->role==0)
            $success['courses'] = $user->courses;
            else
            $success['student info'] = $user->student;
            return $this->sendResponse($success , 'user login successfuly');
        }
        else
            return $this->sendError('Please check your Auth' ,['error'=>'Unauthorised']);




    }

    public function forget(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'email' =>'required|email']);
            if($validator->fails())
            {
                return $this->sendError('Please enter your email' , $validator->errors());
            }
            $email=$request->email;
            if(User::where('email',$email)->doesntExist())
                return sendError('email not found ',['you should regester first'],401);
            $token=substr(md5(rand()), 0, 10);
            DB::table('password_resets')->insert([
                    'email'=>$email,
                    'token'=>$token
                ]
                );

            try{

               return $this->sendResponse( ['email'=>$email,'token'=>$token], 'Reset password mail send on your email');

            }catch(Exception $exception)
            {
                    return sendError('exception ',['message'=>$exception->getMessage()]);
            }

    }
}
