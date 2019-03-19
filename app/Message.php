<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

	protected $fillable = [
        'content', 'is_file', 'channel_id', 'user_id', 'photo_url'
    ];

    public function user() {
		return $this->belongsTo('App\User');
	}

	public function channel() {
		return $this->belongsTo('App\Channel');
	}
}
