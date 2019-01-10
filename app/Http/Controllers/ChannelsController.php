<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;
use App\User;

class ChannelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
  			return response()->json(compact('channel'), 201);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
