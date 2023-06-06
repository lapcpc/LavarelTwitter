<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

// *u* 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Create new tasks
Route::middleware('auth:sanctum')->post('/create/task', function (Request $request) {
    
    $validated = $request->validate([
        'tname' => 'required'
    ]);

    $request->user()->tasks()->create($validated);

    return response()->json(['message' => 'Tuit created']);
});


//Get all tasks from an user
Route::middleware('auth:sanctum')->post('/tasks', function(Request $request){
    $user_id = $request->input('user_id');
    $tasks = Task::all()->where('user_id', $user_id);

    return $tasks;
});




//Create User 
Route::post('/user/create', function(Request $request){
    
    $usuario = User::where('email', $request->input('email'))->first();

    if ($usuario == null){
        $user = new User;
        $user->name = $request->input('name');
    
        $user->tname= "Something wrong";
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return response()->json(['message' => 'User created']);
    }
    else{
        return response('Email already taken', 400);
    }
    


});






Route::post('/login', function (Request $request) {
    
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return response()->json(['message' => 'Succes']);
    }

    return response('Error', 400)->json(['message' => 'Validation went wrong']);
});

Route::get('/logout', function (Request $request){
  
    Auth::logout();
 
    $request->session()->invalidate();
 
    $request->session()->regenerateToken();
 
    return response()->json(['message' => 'User out']);

});



/*
//Token 
Route::post('/token', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
    
    return ['token' => $token->plainTextToken];
});


Ip
Route::get('/ip', function(Request $request){
    $clientIP = $request->ip();

    return $clientIP;
});

//Delete Task
Route::delete('/tasks/{id}', function(string $id){
    $task = Task::find($id);
 
    $task->delete();

    return 'Tarea eliminada';
});

//Edit task
Route::patch('/tasks/{id}', function(string $id){
    $task = Task::find($id);
 
    $task->tname = 'Tarea finalizada';
 
    $task->save();

    return response()->json(['message' => 'Tarea Actualizada']);
});
//Get task by Id 
Route::get('/tasks/{id}', function(string $id){
    
    $task = Task::find($id);
    return  $task;
});
//Get all tasks 
Route::get('/tasks', function(){
    $tasks = Task::all();
    return $tasks;
});


*/