<?php

namespace App\Http\Controllers;

use App\User;
use App\Team;
use Illuminate\Http\Request;

class PhotosController extends Controller
{
    public function userPhoto(Request $request){
      $file = $request->file('file');
      $fileName = time() . '.' . $file->getClientOriginalExtension();
      $s3 = \Storage::disk('s3');
      $filePath = '/profile-photos/' . $fileName;
      $s3->put($filePath, file_get_contents($file), 'public');

      $user = User::findOrFail($this->user()->id);

      $user->photo_url = "https://s3-us-west-2.amazonaws.com/vimbel-test/profile-photos/" . $fileName;

      $user->save();

      return response()->json($user, 200);
    }

    public function messageFile(Request $request){
      //return response()->json($request->file('file'), 200);
      $file = $request->file('file');
      $type = $request->input('type');
      $fileName = $request->input('file_name');
      $message_id = $request->input('message_id');

      try {
        $file = base64_decode($file);
        $fileName = time() . '.' . $fileName;
        $s3 = \Storage::disk('s3');
        $filePath = '/message-photos/' . $fileName;
        $s3->put($filePath, $file, 'public');
        $fileUrl = "https://s3-us-west-2.amazonaws.com/vimbel-test/message-photos/" . $fileName;
      } catch (JWTException $e) {
          return response()->json(['error' => $e], 500);
      }

      return response()->json(compact('fileUrl', 'type', 'file'), 200);
    }

    public function teamPhoto(Request $request){
      //TODO make sure user has permission to change profile photo
      $file = $request->file('file');
      $team_id = $request->input('team_id');
      $fileName = time() . '.' . $file->getClientOriginalExtension();
      $s3 = \Storage::disk('s3');
      $filePath = '/profile-photos/' . $fileName;
      $s3->put($filePath, file_get_contents($file), 'public');

      $team = Team::findOrFail($team_id);

      $team->photo_url = "https://s3-us-west-2.amazonaws.com/vimbel-test/team-profile-photos/" . $fileName;

      $team->save();

      return response()->json($team, 200);
    }
}
