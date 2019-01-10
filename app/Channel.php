<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
	protected $fillable = [
        'name', 'description', 'is_dm', 'is_private', 'team_id'
    ];

    public function teams() {
		return $this->belongsTo('App\Team');
	}

	public function users() {
		return $this->belongsToMany('App\User');
	}

	public function messages() {
		return $this->hasMany('App\Message');
	}
}
