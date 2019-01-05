<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamsRegistrationController extends Controller
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
			'team_id' => 'required',
			'user_id' => 'required'
		]);

		$team_id = $request->input('team_id');
		$user_id = $request->input('user_id');

		$team = [
			'name' => 'Vimbel Department',
			'team_id' => $team_id
		];

		$user = [
			'name' => 'My Name'
		];
		$response = [
			'msg' => 'Member added to team!',
			'team' => $team,
			'user' => $user
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
