<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5',
            'password_confirmation' => 'required|min:5'
        ]);

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $password_confirmation = $request->input('password_confirmation');

        if($password != $password_confirmation){
            return response()->json(['error' => 'Passwords do not match'], 400);
        }

        #// TODO: check for username and email uniqueness

        $user = new User([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username,
            'password' => Hash::make($password),
            'email' => $email,
        ]);

        if ($user->save()) {
            $token = JWTAuth::fromUser($user);

            $user = User::with(['teams', 'invites'])->where("email", $request->input('email'))->first();

            return response()->json(compact('user','token'), 201);
        }

        $response = [
            'msg' => 'An error occurred'
        ];

        return response()->json($response, 404);
    }

    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Email and Password do not match'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = User::with(['teams', 'invites'])->where("email", $request->input('email'))->first();

        return response()->json(compact('user', 'token'), 200);
    }

    public function getAuthenticatedUser()
    {
          try {

                  if (! $user = JWTAuth::parseToken()->authenticate()) {
                          return response()->json(['user_not_found'], 404);
                  }

          } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                  return response()->json(['token_expired'], $e->getStatusCode());

          } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                  return response()->json(['token_invalid'], $e->getStatusCode());

          } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                  return response()->json(['token_absent'], $e->getStatusCode());

          }

          return response()->json(compact('user'));
    }

    public function validateUser(Request $request)
    {
      $user = User::with(['teams', 'invites'])->where("id", $this->user()->id)->first();
      $token = JWTAuth::fromUser($this->user());

      return response()->json(compact('user', 'token'), 200);
    }

    public function getUserData(Request $request){
      $this->validate($request, [
          'user_id' => 'required',
      ]);
      //// TODO: make sure this user can view data from that user
      $user = User::where("id", $request->input('user_id'))->first();

      return response()->json(compact('user'), 200);
    }
}
