<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Kost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class KostController extends Controller {
	public $successStatus = 200;

	public function index(Request $request) {
		$name = $request->query('name');
		$city = $request->query('city');
		$price = $request->query('price');
    $sort = $request->query('sort');
    $sortBy = $request->query('by'); 

		$kosts = $this->getData($name, $city, $price, $sort, $sortBy);

    return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Success',
      'data' => $kosts
    ], $this->successStatus);
	}

	public function getData($name, $city, $price, $sort, $sortBy) {
		$all = Kost::all();
		$sortBy = ($sortBy != null) ? $sortBy : 'available_rooms';

		if ($city != null) {
			$all = $all->where('city', $city);
		}

		if ($sort == 'desc'){
			$all = $all->sortByDesc($sortBy)->values()->all();
		} else {
			$all = $all->sortBy($sortBy)->values()->all();
		}
		return $all;
	}

	public function store(Request $request) {
		$user = Auth::user();

		if ($user['role'] == 0) {
			return response()->json([
        'code' => 401,
        'status' => false,
        'message' => 'You have no permission to do this action',
      ], 401);
		}

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'city' => 'required',
			'room_length' => 'required|numeric|min:1',
			'room_width' => 'required|numeric|min:1',
			'available_rooms' => 'required|numeric|min:0',
			'price' => 'required|numeric'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'code' => 400,
        'status' => false,
        'message' => $validator->errors()
      ], 400);
		}
		
		$input = $request->all();
		$input['owner_id'] = $user['id'];

		$kost = Kost::create($input);
		$kost['room_size'] = $kost->roomSize();

    return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Add kost success',
      'data' => $kost
    ], $this->successStatus);
	}

	public function show($id) {
		$kost = Kost::find($id);
		if ($kost == null) {
			return response()->json([
				'code' => 204,
				'status' => true,
				'message' => 'Kost not exist'
			], 204);
		}
		$kost['room_size'] = $kost->roomSize();
		return response()->json([
			'code' => $this->successStatus,
      'status' => true,
      'message' => 'Show kost detail success',
      'data' => $kost
		], 200);
	}

	public function update(Request $request, $id) {
		$user = Auth::user();
		$kost = Kost::find($id);

		if ($user['role'] == 0 || $kost['owner_id'] != $user['id']) {
			return response()->json([
        'code' => 401,
        'status' => false,
        'message' => 'You have no permission to do this action',
      ], 401);
		}

		if ($kost == null) {
			return response()->json([
				'code' => 204,
				'status' => true,
				'message' => 'Kost not exist',
				'data' => []
			], 204);
		}

		if ($request['name'] != null) { $kost['name'] = $request['name']; }
		if ($request['room_length'] != null) { $kost['room_length'] = $request['room_length']; }
		if ($request['room_width'] != null) { $kost['room_width'] = $request['room_width']; }
		if ($request['price'] != null) { $kost['price'] = $request['price']; }
		
		$kost->save();
		$kost['room_size'] = $kost->roomSize();

		return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Update kost success',
      'data' => $kost
    ], $this->successStatus);
	}

	public function destroy($id) {
		$user = Auth::user();
		$kost = Kost::find($id);

		if ($user['role'] == 0 || $kost['owner_id'] != $user['id']) {
			return response()->json([
        'code' => 401,
        'status' => false,
        'message' => 'You have no permission to do this action',
      ], 401);
		}
		
		if ($kost == null) {
			return response()->json([
				'code' => 204,
				'status' => true,
				'message' => 'Kost not exist'
			], 204);
		}

		Kost::destroy($id);
		return response()->json([
      'code' => $this->successStatus,
      'status' => true,
      'message' => 'Delete kost success'
    ], $this->successStatus);
	}
}
?>