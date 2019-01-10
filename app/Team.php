<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'string', 'name',
    ];

	public function users() {
		return $this->belongsToMany('App\User');
	}
	public function channels() {
		return $this->hasMany('App\Channel');
	}
  public function invites(){
    return $this->hasMany('App/Invite');
  }
}
