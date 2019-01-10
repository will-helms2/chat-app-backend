<?php

namespace App\Http\Controllers;
use App\Team;
use App\User;
use Illuminate\Http\Request;

class TeamsRegistrationController extends Controller
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
			'team_id' => 'required'
		]);

		$team_id = $request->input('team_id');
		$team = Team::findOrFail($team_id);

		$message = [
			'error' => 'User is already registered to Team',
			'user' => $this->user(),
			'team' => $team
		];
		if($team->users()->where('users.id', $this->user()->id)->first()) {
			return response()->json($message, 404);
		}
    #// TODO: check to make sure the user has an invite. Security measure before Prod
    //if($this->user()->invites())

		$this->user()->teams()->attach($team); // Adds to pivot table

		$response = [
			'msg' => 'Member added to team!',
			'team' => $team,
			'user' => $this->user()
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
        $team = [
			'name' => 'Vimbel Department',
			'desc' => 'Development of Vimbel App'
		];

		$user = [
			'name' => 'My Name'
		];
		$response = [
			'msg' => "Member removed from team {$id}!",
			'team' => $team,
			'user' => $user
		];

			return response()->json($response, 200);
    }
}
