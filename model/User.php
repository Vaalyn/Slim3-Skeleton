<?php
	namespace Model;

	use Illuminate\Database\Eloquent\Model;

	class User extends Model {

		protected $table = 'user';
		protected $primaryKey = 'user_id';
	}
?>
