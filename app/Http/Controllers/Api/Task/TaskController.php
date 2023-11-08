<?php

namespace App\Http\Controllers\Api\Task;
use Validator;
use App\Traits\GeneralTrait;
// use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\Tasks\Task;
use App\Models\User;
class TaskController extends Controller
{
    use GeneralTrait;
    public function updatestatus(Request $request) {
        $user=Auth::user();
        if($user->role =='employee'){
        $task=Task::where('id',$request->id)->first();
        if(!$task)
           return $this->returnError(404,"id not found");


        $taskval = [
            'id'=>'required|integer',
            "status" => "required",
        ];
        $task->update(
            [

                'status'=>$request->status,
            ]
        );
        $validator = Validator::make ($request->all(),$taskval) ;
        if($validator->fails ()) {
              $code = $this->returnCodeAccordingToInput ($validator);
             return $this->returnValidationError ($code, $validator);
            }


        return $this->returnSuccessMessage('status updated','200');
     }
        else {
        return $this->returnError('','You are not an employee');
        }

        }

    public function updatetask(Request $request){
        $user=Auth::user();
        if($user->role !='employee'){
        $task=Task::where('id',$request->id)->first();
        if(!$task)
           return $this->returnError(404,"id not found");


        $taskval = [
            'id'=>'required|integer',
             // "status" => "required",
        ];
        $task->update(
            [

                'title'=>$request->title,
                'description'=>$request->description,
                'deadline'=>$request->deadline,
                'status'=>$request->status,
            ]
        );
        $validator = Validator::make ($request->all(),$taskval) ;
        if($validator->fails ()) {
              $code = $this->returnCodeAccordingToInput ($validator);
             return $this->returnValidationError ($code, $validator);
            }


        return $this->returnSuccessMessage('Data updated','200');
      }
      else {
       return $this->returnError('','You are not an admin or teamleader');
      }
    }
    public function deletetask(Request $request){
        if($request->id->delete()){
            return $this->returnSuccessMessage('deleted seccessfully','');
        }
    }
    public function ShowAllTasks(){

        $task = Task::get();
        if(!$task)
         return $this->returnError(404,"No Data");

        return $this->returnData('Users',$task,"This is your Rules.");
    }
    public function createtask(Request $request) {
        $user=Auth::user();
        if($user->role !='employee'){
        try{
            $task = [
                "title" => "required",
                "description" => "required",
                "deadline" => "required",
                "status" => "required",
                // "created_by" => "required",
                // "user_id" => "required",

            ];
            $validator = Validator::make ($request->all(),$task) ;
            if($validator->fails ()) {
            $code = $this->returnCodeAccordingToInput ($validator);
            return $this->returnValidationError ($code, $validator);

            }
            $user=Auth::user();

           //create task
           if($user->role != 'employee')
           {
            Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'status' => $request->status,
                'created_by' => $user->name,
                  'user_id' =>$user->id,

                ]  );

           }
           else
           {
            return $this->returnError(404,"you cant add task you are just an employee");
           }


        }
        catch(\Exception $ex) {
        return $this->returnError($ex->getCode(),$ex->getMessage());
                }


            }
            else {
            return $this->returnError('','You are an employee..');
            }
    }
}
