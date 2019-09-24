<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class ActivityController extends Controller {
	public $successStatus = 200;

	public function askQuestion(Request $request) {
		$user = Auth::user();
		$credit_available = $user['credits'];

		if ($credit_available - $request['credit_usage'] < 0) {
			return response()->json([
				'code' => $this->successStatus,
				'status' => true,
				'message' => 'Not enough credits',
				'data' => []
			], $this->successStatus);
		}

		$recipient = User::where('id', $request['recipient_id'])->where('role', '1')->first();
		if ($recipient == null){
			return response()->json([
				'code' => 200,
				'status' => true,
				'message' => 'No owner',
				'data' => $recipient
			], 200);
		}

		$input = $request->all();
		$input['user_id'] = $user['id'];
		$input['credit_left'] = $credit_available - $request['credit_usage'];

		$activity = UserActivityLog::create($input);

		$user['credits'] = $input['credit_left'];
		$user->save();

		return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Add activity success',
      'data' => $activity
    ], $this->successStatus);
	}
}
?>