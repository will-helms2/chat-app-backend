<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;
use App\User;
use App\Message;

class ChannelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $this->validate($request, [
        'team_id' => 'Integer',
        'public_only' => 'Boolean'
  		]);

      $team_id = $request->input('team_id');
      $public_only = $request->input('public_only', false);

      if($team_id && !$public_only){
        //all channels the user has for a team
        $channels = $this->user()->channels()->where("team_id", $team_id)->get();
      }elseif($team_id && $public_only){
        //public channels the user is not in
        $channels = Channel::whereDoesntHave('users', function ($query) {
            $query->where('user_id', '=', $this->user()->id);
        })->where([["channels.team_id", $team_id], ["channels.is_private", false]])->get();
      }else{
        //all channels
        $channels = $this->user()->channels()->get();
      }
      return response()->json($channels, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  		$this->validate($request, [
  			'name' => 'required|String',
  			'description' => 'String',
        'is_dm' => 'required|Boolean',
        'is_private' => 'required|Boolean',
        'team_id' => 'required'
  		]);

  		$name = $request->input('name');
  		$description = $request->input('description');
      $is_dm = $request->input('is_dm');
      $is_private = $request->input('is_private');
      $team_id = $request->input('team_id');

      #// TODO: make sure the user has access to the team to create a channel

  		$channel = new Channel([
        'team_id' => $team_id,
  			'name' => $name,
  			'description' => $description,
  			'is_dm' => $is_dm,
  			'is_private' => $is_private
  		]);

  		if ($channel->save()) {
        User::findOrFail($this->user()->id)->channels()->attach($channel);
  			return response()->json($channel, 201);
  		}

  		$response = [
  			'error' => 'An error has occured!'
  		];
  		return response()->json($response, 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      #// TODO: Make sure the user has accress to the team they are trying to load
      $channel = Channel::with(['users'])->findOrFail($id);
      $channel['messages'] = Message::with(['user'])->where("channel_id", "=", $channel->id)->orderBy("created_at", "desc")->get();


      return response()->json($channel, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $this->validate($request, [
        'name' => 'String',
        'description' => 'String',
        'is_private' => 'Boolean',
        'team_id' => 'required'
      ]);

      $name = $request->input('name');
      $description = $request->input('description');
      $is_private = $request->input('is_private');

      #// TODO: make sure the user has access to the team to create a channel

      $channel = Channel::findOrFail($id);

      if($name){
        $channel->name = $name;
      }
      if($description){
        $channel->description = $description;
      }
      if($is_private){
        $channel->is_private = $is_private;
      }

      if ($channel->save()) {
        return response()->json($channel, 201);
      }

      $response = [
        'error' => 'An error has occured!'
      ];
      return response()->json($response, 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $channel = Channel::with(['users'])->findOrFail($id);
        $channel->delete();
        $channel->save();
        return response()->json("deleted", 200);
    }

    public function createDM(Request $request)
    {
  		$this->validate($request, [
        'team_id' => 'required',
        'user_id' => 'required',
  		]);

  		$name = "Direct Message";
  		$description = "message";
      $is_dm = true;
      $is_private = true;
      $team_id = $request->input('team_id');
      $user_id = $request->input('user_id');

      #// TODO: make sure the user has access to the team to create a channel

  		$channel = new Channel([
        'team_id' => $team_id,
  			'name' => $name,
  			'description' => $description,
  			'is_dm' => $is_dm,
  			'is_private' => $is_private
  		]);

  		if ($channel->save()) {
        User::findOrFail($user_id)->channels()->attach($channel);
        $this->user()->channels()->attach($channel);
  			return response()->json($channel, 201);
  		}

  		$response = [
  			'error' => 'An error has occured!'
  		];
  		return response()->json($response, 500);
    }
}
