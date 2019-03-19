<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Events\MessageSent;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $this->validate($request, [
        'channel_id' => 'required',
        'files_only' => 'String'
      ]);

      $channel_id = $request->input('channel_id');
      $files_only = $request->input('files_only', false);

      if(!$files_only){
        $messages = Message::where('channel_id', $channel_id)->get();
      }else{
        $messages = Message::where([['is_file', 1],['channel_id', $channel_id]])->get();
      }

      return response()->json($messages, 201);


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
          'channel_id' => 'required',
          'user_id' => 'required',
          'content' => 'required|String',
          'is_file' => 'required|Boolean',
          'photo_url' => 'String'
        ]);

        $channel_id = $request->input('channel_id');
        $user_id = $request->input('user_id');
        $content = $request->input('content');
        $is_file = $request->input('is_file');
        $photo_url = $request->input('photo_url');

        #// TODO: make sure the user has access to this channel

        $message = new Message([
          'channel_id' => $channel_id,
          'user_id' => $user_id,
          'content' => $content,
          'is_file' => $is_file,
          'photo_url' => $photo_url,
        ]);

        if ($message->save()) {
          $fullMessage = Message::with(['user'])->findOrFail($message->id);
          broadcast(new MessageSent($this->user(), $fullMessage, $channel_id))->toOthers();
          return response()->json($message, 201);
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
