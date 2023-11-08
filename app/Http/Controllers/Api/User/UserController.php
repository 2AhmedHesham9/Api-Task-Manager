<?php

namespace App\Http\Controllers\Api\User;
use App\Models\Tasks\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Traits\GeneralTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    use GeneralTrait;


    public function updateuser(Request $request){
        $user=Auth::user();
        if($user->role =='admin'){
        $user=User::where('id',$request->id)->first();
        if(!$user)
           return $this->returnError(404,"id not found");


        $userval = [
            'id'=>'required|integer',
            'name'=>'required',
            'email'=>'required|exists:users,email',
            'password'=>'required',
            'img_path'=>'required',
            'role'=>'required',

        ];
        $user->update(
            [

                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>$request->password,
                'img_path'=>$request->img_path,
                'role'=>$request->role,
            ]
        );
        $validator = Validator::make ($request->all(),$userval) ;
        if($validator->fails ()) {
              $code = $this->returnCodeAccordingToInput ($validator);
             return $this->returnValidationError ($code, $validator);
            }


        return $this->returnSuccessMessage('Data updated','200');
        }
        else {
            return $this->returnError('','You are not an admin');
           }
    }


    public function deleteuser(Request $request){
        $user=Auth::user();
        if($user->role =='admin')
       {
        $user = User::find($request->id);

        if($user){
            $user->delete();
            return $this->returnSuccessMessage('deleted seccessfully','');
        }
       }
       else {
        return $this->returnError('','You are not an admin');
       }
    }
    public function index() {
        // $user = Auth::user();
        $users = User::get();
        if(!$users)
         return $this->returnError(404,"No Data");

        return $this->returnData('Users',$users,"This is your data.");

    }
    public function register(Request $request){
        try{
            $rules = [
                "name" => "required",
                "email" => "required",
                "password" => "required",
                "img_path" => "required",
                "role" => "required",


            ];
            $validator = Validator::make ($request->all(),$rules) ;
            if($validator->fails ()) {
            $code = $this->returnCodeAccordingToInput ($validator);
            return $this->returnValidationError ($code, $validator);

            }

            $user=Auth::user();
            //register
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'img_path' => $request->img_path,
                'role' => $request->role,
                'leader_id'=>$user->id,

            ]  );




        }
        catch(\Exception $ex) {
        return $this->returnError($ex->getCode(),$ex->getMessage());
                }

    }
    public function gettasks(){
        $user=Auth::user();
        $tasks=Task::where('user_id',$user->leader_id)->get();

        return $this->returnData('1',$tasks,'your tasks');



    }
    public function login(Request $request){













        //validation
        try{
            $rules = [
                "email" => "required|exists:users,email",
                "password" => "required"
                //|exists:admins,password
            ];
            $validator = Validator::make ($request->all(),$rules) ;
            if($validator->fails ()) {
            $code = $this->returnCodeAccordingToInput ($validator);
            return $this->returnValidationError ($code, $validator);

            }

            //login

            $credentials = $request->only(['email', 'password']);
            $token=Auth::guard('user-api')->attempt($credentials);


            if(!$token) {
                return $this->returnError('E001','data enterd in invalid');
            }

            $user=Auth::guard('user-api')->user();
            $user->api_token=$token;
            //return token and data
            return $this->returnData('User',$user,'your Data');

        }
        catch(\Exception $ex) {
        return $this->returnError($ex->getCode(),$ex->getMessage());
                }
            }

}
