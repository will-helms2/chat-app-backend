<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

	protected $fillable = [
        'content', 'is_file', 'channel_id', 'user_id'
    ];

    public function users() {
		return $this->belongsTo('App\User');
	}

	public function channels() {
		return $this->belongsTo('App\Channel');
	}
}
