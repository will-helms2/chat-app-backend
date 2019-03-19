<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Invite;
use DB;

class InvitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $team_invites = Invite::with(['team:id,name,string', 'ownedUser:id,first_name,last_name,email'])->where('invited_user_id',$this->user()->id)->get();

      return response()->json($team_invites, 200);
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
    			'email' => 'required|email',
          'team_id' => 'required',
    		]);

        #// TODO: make sure the user has access to this team

    		$invited_email = $request->input('email');
        $invited_user_id = User::where('email',$invited_email)->first()->id;
        $team_id = $request->input('team_id');

    		$invite = new Invite([
    			'invited_user_id' => $invited_user_id,
    			'owner_user_id' => $this->user()->id,
          'team_id' => $team_id,
    		]);

    		if ($invite->save()) {
    			return response()->json(compact('invite'), 201);
    		}

    		$response = [
    			'msg' => 'An error has occured!'
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
        //
    }
}
