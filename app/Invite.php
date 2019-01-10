<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{

	protected $fillable = [
        'team_id', 'invited_user_id', 'owner_user_id'
    ];

  public function invitedUser() {
		return $this->belongsTo('App\User', 'invited_user_id', 'id');
	}

  public function ownedUser() {
		return $this->belongsTo('App\User', 'owner_user_id', 'id');
  }

	public function team() {
		return $this->belongsTo('App\Team');
	}
}
