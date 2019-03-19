<?php

namespace App\Http\Controllers;
use App\Team;
use App\User;
use Illuminate\Http\Request;

class ChannelRegistrationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  		$this->validate($request, [
  			'channel_id' => 'required',
  		]);

  		$channel_id = $request->input('channel_id');
      $channel = Channel::findOrFail($channel_id);

      $message = [
  			'error' => 'User is already added to this channel'
  		];
  		if($channel->users()->where('users.id', $this->user()->id)->first()) {
  			return response()->json($message, 404);
  		}

      #// TODO: check to make sure the user requesting has access to the private channel or if the channel is public


      if($user_id = $request->input('user_id')){
        User::findOrFail($user_id)->channels()->attach($channel);
      }else{
        $this->user()->channels()->attach($channel);
      }

  		$response = [
  			'msg' => 'Member added to team!',
  			'team' => $team,
  			'user' => ($user_id ? User::findOrFail($user_id)->get() : $this->user())
  		];

  		return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
