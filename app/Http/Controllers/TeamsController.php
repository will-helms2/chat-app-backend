<?php

namespace App\Http\Controllers;
use App\Team;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $team = [
			'name' => $name,
			'desc' => $desc,
			'team_id' => $team_id
		];
		$response = [
			'msg' => 'Welcome back to team!',
		];

		return response()->json($response, 200);
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
        //return "It works";
		$team = new Team([
			'name' => $name,
			'string' => $desc,
		]);

		if ($team->save()) {
			$response = [
				'team' => $team
			];
			return response()->json($response, 201);
		}

		$response = [
			'msg' => 'An error has occured!'
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
        return "It works";
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
