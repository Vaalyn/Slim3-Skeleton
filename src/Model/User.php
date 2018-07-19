<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model {
	use SoftDeletes;

	protected $table      = 'user';
	protected $primaryKey = 'user_id';
	protected $dates      = ['created_at', 'updated_at', 'deleted_at'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function authTokens(): HasMany {
	    return $this->hasMany(AuthToken::class, 'user_id');
	}
}