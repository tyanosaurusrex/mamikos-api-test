<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller {
  public $successStatus = 200;

  public function login() {
    if (Auth::attempt(['email' => request('email'), 'password' => request('password')])){
      $user = Auth::user();
      $success['token'] = $user->createToken('App')->accessToken;
      
      return response()->json([
        'code' => $this->successStatus,
        'status' => true,
        'message' => 'Login success',
        'data' => $success
      ], $this->successStatus);
    } else {
      return response()->json([
        'code' => 401,
        'status' => false,
        'message' => 'Login failed',
      ], 401);
    }
  }

  public function register(Request $request) {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email',
      'password' => 'required',
      'role' => 'required|numeric|min:0|max:1',
      'confirm_password' => 'required|same:password',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'code' => 401,
        'status' => false,
        'message' => $validator->errors()
      ], 401);
    }

    $input = $request->all();
    $input['password'] = bcrypt($input['password']);
    $user = User::create($input);
    $success['token'] = $user->createToken('App')->accessToken;
    $success['name'] = $user->name;
    
    return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Register success',
      'data' => $success
    ], $this->successStatus);
  }

  public function details() {
    $user = Auth::user();
    return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Get user data',
      'data' => $user
    ], $this->successStatus);
  }

  public function logout(Request $request) {
    $token = $request->user()->token();
    $token->revoke();

    return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Logout success',
      'data' => []
    ], $this->successStatus);
  }
}

?>