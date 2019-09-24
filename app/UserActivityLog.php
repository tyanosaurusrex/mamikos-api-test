<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model {
	protected $table = 'user_activity_logs';

	protected $fillable = [
		'user_id', 'recipient_id', 'activity', 'credit_usage', 'credit_left'
	];
}
?>