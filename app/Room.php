<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model {
	protected $table = 'rooms';

	protected $fillable = [
		'building_id', 'name', 'room_length', 'room_width', 'price', 'is_available'
	];

	public function building() {
		return $this->belongsTo('App\Building');
	}
}
?>