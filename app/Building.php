<?php 

namespace App;

class Building extends Eloquent {
	protected $table = 'buildings';

	protected $fillable = [
		'name', 'address', 'city', 'owner_id'
	];
}
?>