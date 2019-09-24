<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kost extends Model {
	protected $table = 'kosts';

	protected $fillable = [
		'owner_id', 'city', 'name', 'room_length', 'room_width', 'price', 'available_rooms'
	];

	protected $hidden = [
		'room_length', 'room_width'
	];

	public function roomSize() {
		return $this->room_length . ' x ' . $this->room_width;
	}

	public function user() {
		return $this->belongsTo('App\User');
	}
}
?>