<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Rule\RuleController;
use App\Http\Controllers\Api\Task\TaskController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//midleware for admin

Route::group(['middleware'=>['api','checkpassword']], function(){

    // make new group just admin know it to register acount as admin remember to make it................
  // Route::post('register',[UserController::class,'register']);

    Route::group(['prefix' => 'admin'], function(){

        Route::post('register',[UserController::class,'register'])->middleware('auth.guard:user-api');
        Route::post('login',[UserController::class,'login']);

        //for user
        Route::post('ShowAllUsers',[UserController::class,'index']);
        Route::post('updateuser',[UserController::class,'updateuser'])->middleware('auth.guard:user-api');
        Route::post('deleteuser',[UserController::class,'deleteuser'])->middleware('auth.guard:user-api');

        //for rules
        Route::post('storerule',[RuleController::class,'storerule'])->middleware('auth.guard:user-api');
        Route::post('showerules',[RuleController::class,'showrules']);
        Route::post('updaterule',[RuleController::class,'updaterule'])->middleware('auth.guard:user-api');
        Route::post('deleterule',[RuleController::class,'deleterule'])->middleware('auth.guard:user-api');

        //for tasks
        Route::post('ShowAllTasks',[TaskController::class,'ShowAllTasks']);
        Route::post('createtask',[TaskController::class,'createtask'])->middleware('auth.guard:user-api');
        Route::post('updatetask',[TaskController::class,'updatetask'])->middleware('auth.guard:user-api');
        Route::post('deletetask',[TaskController::class,'deletetask'])->middleware('auth.guard:user-api');
    });

});


Route::group(['middleware'=>['api']], function(){
    Route::group(['prefix' => 'teamleader'], function(){
        Route::post('register',[UserController::class,'register'])->middleware('auth.guard:user-api');
        Route::post('login',[UserController::class,'login']);
        Route::post('showerules',[RuleController::class,'showrules']);
        Route::post('createtask',[TaskController::class,'createtask'])->middleware('auth.guard:user-api');
        Route::post('updatetask',[TaskController::class,'updatetask'])->middleware('auth.guard:user-api');
        Route::post('deletetask',[TaskController::class,'deletetask'])->middleware('auth.guard:user-api');
    });

});


    Route::group(['prefix' => 'employee'], function(){
        Route::post('login',[UserController::class,'login']);
        Route::post('Mytasks',[UserController::class,'gettasks'])->middleware('auth.guard:user-api');
        Route::post('updatestatus',[TaskController::class,'updatestatus'])->middleware('auth.guard:user-api');
    });
