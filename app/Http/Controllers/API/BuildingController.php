<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Building;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class BuildingController extends Controller {
  public $successStatus = 200;

	public function index() {}
	public function store(Request $request) {
		$user = Auth::user();

		if ($user['role'] == 0) {
			return response()->json([
        'code' => 401,
        'status' => false,
        'message' => 'This feature only available for owner',
      ], 401);
		}

		$validator = Validator::make($request->all(), [
      'name' => 'required',
      'address' => 'required',
      'city' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'code' => 401,
        'status' => false,
        'message' => $validator->errors()
      ], 401);
		}
		
		$input = $request->all();
		$input['available_rooms'] = 0;
		$input['owner_id'] = $user['id'];

    $building = Building::create($input);
    
    return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Add building success',
      'data' => $building
    ], $this->successStatus);
	}

	public function show() {}
	public function update() {}
	public function delete() {}
}
?>