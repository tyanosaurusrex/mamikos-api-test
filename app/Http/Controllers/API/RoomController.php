<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Building;
use App\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class RoomController extends Controller {
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
      'building_id' => 'required',
			'room_length' => 'required|numeric|min:1',
			'room_width' => 'required|numeric|min:1',
			'price' => 'required|numeric'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'code' => 401,
        'status' => false,
        'message' => $validator->errors()
      ], 401);
		}
		
		$input = $request->all();
		$input['is_available'] = "1";

		$room = Room::create($input);
		$room['room_size'] = $input['room_length'] . ' x ' . $input['room_width'];

		$this->updateAvailableRooms($input['building_id'], 1);
    
    return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Add room success',
      'data' => $room
    ], $this->successStatus);
	}

	public function show() {}
	public function update(Request $request, $id) {
		$user = Auth::user();

		if ($user['role'] == 0) {
			return response()->json([
        'code' => 401,
        'status' => false,
        'message' => 'This feature only available for owner',
      ], 401);
		}

		$room = Room::find($id);
		if ($room == null) {
			return response()->json([
				'code' => $this->successStatus,
				'status' => true,
				'message' => 'There is no room with ID ' . $id,
				'data' => []
			], $this->successStatus);
		}

		if ($request['name'] != null) { $room['name'] = $request['name']; }
		if ($request['room_length'] != null) { $room['room_length'] = $request['room_length']; }
		if ($request['room_width'] != null) { $room['room_width'] = $request['room_width']; }
		if ($request['price'] != null) { $room['price'] = $request['price']; }

		if ($request['is_available'] != null && $room['is_available'] != $request['is_available']) { 
			$room['is_available'] = $request['is_available']; 

			$operation = $request['is_available'] == "1" ? 1 : 0;
			$this->updateAvailableRooms($room['building_id'], $operation);
		}
		
		$room->save();

		return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Update room success',
      'data' => $room
    ], $this->successStatus);
	}

	public function delete() {}

	public function updateAvailableRooms($id, $isAdded) {
		$building = Building::find($id);

		if ($isAdded == 1){
			$building['available_rooms'] += 1;
		} else {
			$building['available_rooms'] -= 1;
		}
		$building->save();
		
		return $building;
	}
}
?>