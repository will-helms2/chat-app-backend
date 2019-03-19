<?php

namespace App\Http\Controllers;
use App\Team;
use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use App\Channel;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
  		$teams = $this->user()->teams()->get();
  		return response()->json($teams, 200);
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
  			'name' => 'required',
  			'string' => 'required',
  		]);

  		$name = $request->input('name');
  		$desc = $request->input('string');

  		$team = new Team([
  			'name' => $name,
  			'string' => $desc,
  		]);

  		if ($team->save()) {

        $channel = new Channel([
          'team_id' => $team->id,
    			'name' => 'general',
    			'description' => 'This is where your team does some basic chat',
    			'is_dm' => 0,
    			'is_private' => 0
    		]);

        $this->user()->teams()->attach($team);

        if($channel->save()){
          $this->user()->channels()->attach($channel);
        }

  			return response()->json($team, 201);
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
      $team = Team::with('users')->findOrFail($id);
      $team["direct_messages"] = $this->user()->channels()->with(['users' => function($query) {
          $query->where('user_id', '!=', $this->user()->id);
      }])->where([["is_dm", true],["team_id", $team->id]])->get();
      $team["channels"] = $this->user()->channels()->where([["is_dm", false],["team_id", $team->id]])->get();
      $team["public_channels"] = Channel::whereDoesntHave('users', function ($query) {
          $query->where('user_id', '=', $this->user()->id);
      })->where([["channels.team_id", $team->id], ["channels.is_private", false]])->get();

      return response()->json($team, 200);
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
			'name' => 'required',
			'string' => 'required',
			'id' => 'required'
		]);

		$name = $request->input('name');
		$desc = $request->input('string');
		$team_id = $request->input('id');

		$team = [
			'name' => $name,
			'desc' => $desc,
			'team_id' => $team_id
		];
		$response = [
			'msg' => 'Successfully updated team!',
			'team' => $team
		];

		return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $response = [
			'msg' => 'Team has been deleted!',
			'team_id' => $id
		];

		return response()->json($response, 200);
    }
}
