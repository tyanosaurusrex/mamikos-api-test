<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model {
	protected $table = 'buildings';

	protected $fillable = [
		'name', 'address', 'city', 'available_rooms', 'owner_id'
	];

	protected $hidden = [
		'room_length', 'room_width'
	];

	public function rooms() {
		return $this->hasMany('App\Rooms');
	}
}
?>