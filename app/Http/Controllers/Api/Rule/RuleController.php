<?php

namespace App\Http\Controllers\Api\Rule;
use App\Models\User;
use Validator;
use App\Traits\GeneralTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;
use  App\Models\Rules\Rule;



class RuleController extends Controller
{
    use GeneralTrait;
    public function showrules() {

        $rules = Rule::get();
        if(!$rules)
         return $this->returnError(404,"No Data");

        return $this->returnData('Users',$rules,"This is your Rules.");

    }

    public function storerule(Request $request){
        try{
            $rules = [
                "instructions" => "required",
                "attendance" => "required",
                "off_day" => "required",


            ];
            $validator = Validator::make ($request->all(),$rules) ;
            if($validator->fails ()) {
            $code = $this->returnCodeAccordingToInput ($validator);
            return $this->returnValidationError ($code, $validator);

            }


            //create task
            Rule::create([
                'instructions' => $request->instructions,
                'attendance' => $request->attendance,
                'off_day' => $request->off_day,
            ]  );



        }
        catch(\Exception $ex) {
        return $this->returnError($ex->getCode(),$ex->getMessage());
                }

    }

    public function updaterule(Request $request){
        $rule=Rule::where('id',$request->id)->first();
        if(!$rule)
           return $this->returnError(404,"id not found");


        $ruleval = [
            'id'=>'required|integer',
            'instructions'=>'required',
            'attendance'=>'required',
            'off_day'=>'required',
        ];
        $rule->update(
            [

                'instructions'=>$request->instructions,
                'attendance'=>$request->attendance,
                'off_day'=>$request->off_day,

            ]
        );
        $validator = Validator::make ($request->all(),$ruleval) ;
        if($validator->fails ()) {
              $code = $this->returnCodeAccordingToInput ($validator);
             return $this->returnValidationError ($code, $validator);
            }


        return $this->returnSuccessMessage('rule updated','200');



    }
    public function deleterule(){
        if($request->id->delete()){
            return $this->returnSuccessMessage('deleted seccessfully','');
        }
    }

}
