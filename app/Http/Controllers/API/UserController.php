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
      $success['token'] = $user->createToken('MyApp')->accessToken;
      
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
    $input['premium_user'] = '0';
    $input['credits'] = 20;
    $user = User::create($input);
    
    $success['token'] = $user->createToken('MyApp')->accessToken;
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

  public function updateStatus() {
    $user = Auth::user();

    $user['credits'] = $user['premium_user'] == 0 ? 40 : 20;
    $user['premium_user'] = $user['premium_user'] == 0 ? "1" : "0";
    $user->save();

    return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Update user status success',
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